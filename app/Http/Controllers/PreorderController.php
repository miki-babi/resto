<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\MenuItemAddon;
use App\Models\MenuItemVariant;
use App\Models\PastryItem;
use App\Models\PickupLocation;
use App\Models\PickupLocationHour;
use App\Models\PreOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PreorderController extends Controller
{
    public function showMenuPage()
    {
        $pickupLocations = PickupLocation::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->with([
                'hours' => fn ($query) => $query
                    ->where('is_active', true)
                    ->orderBy('day_of_week')
                    ->orderBy('period')
                    ->orderBy('hour_slot'),
            ])
            ->get(['id', 'name', 'address']);

        $pickupAvailability = $this->buildPickupAvailabilityMap($pickupLocations, 14);

        $menuCategories = MenuCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->with([
                'items' => fn ($query) => $query
                    ->where('is_available', true)
                    ->where('preorder_available', true)
                    ->orderBy('sort_order')
                    ->orderBy('title')
                    ->with([
                        'variants' => fn ($variantQuery) => $variantQuery
                            ->orderBy('sort_order')
                            ->orderBy('name'),
                        'addons' => fn ($addonQuery) => $addonQuery
                            ->where('is_active', true)
                            ->orderBy('sort_order')
                            ->orderBy('name'),
                        'media',
                    ]),
            ])
            ->get()
            ->filter(fn (MenuCategory $category) => $category->items->isNotEmpty())
            ->values();

        $menuItemsCatalog = $menuCategories
            ->flatMap(fn (MenuCategory $category): Collection => $category->items)
            ->unique('id')
            ->values()
            ->map(function (MenuItem $item): array {
                return [
                    'id' => (int) $item->id,
                    'title' => (string) $item->title,
                    'price' => (float) $item->price,
                    'image_url' => (string) ($item->getFirstMediaUrl('menu_images') ?: ''),
                    'variants' => $item->variants
                        ->map(fn (MenuItemVariant $variant): array => [
                            'id' => (int) $variant->id,
                            'name' => (string) $variant->name,
                            'price' => (float) $variant->price,
                        ])
                        ->values()
                        ->all(),
                    'addons' => $item->addons
                        ->map(fn (MenuItemAddon $addon): array => [
                            'id' => (int) $addon->id,
                            'name' => (string) $addon->name,
                            'price' => (float) $addon->price,
                        ])
                        ->values()
                        ->all(),
                ];
            })
            ->values()
            ->all();

        return view('pages.preorder-menu', [
            'pickupLocations' => $pickupLocations,
            'pickupAvailability' => $pickupAvailability,
            'menuCategories' => $menuCategories,
            'menuItemsCatalog' => $menuItemsCatalog,
        ]);
    }

    public function submitMenu(Request $request)
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
            'pickup_location_id' => [
                'required',
                Rule::exists('pickup_locations', 'id')->where(
                    fn ($query) => $query->where('is_active', true)
                ),
            ],
            'pickup_date' => ['required', 'date', 'after_or_equal:today'],
            'pickup_time' => ['required', 'date_format:H:i'],
            'quantities' => ['required', 'array'],
            'quantities.*' => ['nullable', 'integer', 'min:0', 'max:100'],
            'variant_ids' => ['nullable', 'array'],
            'variant_ids.*' => ['nullable', 'integer', 'min:1'],
            'addon_ids' => ['nullable', 'array'],
            'addon_ids.*' => ['nullable', 'array'],
            'addon_ids.*.*' => ['nullable', 'integer', 'min:1'],
        ]);

        $selectedItems = $this->collectSelectedItems($validated['quantities']);

        if ($selectedItems->isEmpty()) {
            throw ValidationException::withMessages([
                'quantities' => 'Please select at least one menu item quantity.',
            ]);
        }

        $menuItems = MenuItem::query()
            ->whereIn('id', $selectedItems->pluck('id')->all())
            ->where('is_available', true)
            ->where('preorder_available', true)
            ->with([
                'variants' => fn ($query) => $query
                    ->orderBy('sort_order')
                    ->orderBy('name'),
                'addons' => fn ($query) => $query
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name'),
            ])
            ->get()
            ->keyBy('id');

        if ($menuItems->count() !== $selectedItems->count()) {
            throw ValidationException::withMessages([
                'quantities' => 'One or more selected menu items are not available for preorder.',
            ]);
        }

        $pickupSlot = $this->resolvePickupSlot(
            (string) $validated['pickup_date'],
            (string) $validated['pickup_time']
        );

        $this->ensurePickupSlotIsAvailable((int) $validated['pickup_location_id'], $pickupSlot);

        $variantIdsByItem = $this->normalizeItemScalarSelections($validated['variant_ids'] ?? []);
        $addonIdsByItem = $this->normalizeItemArraySelections($validated['addon_ids'] ?? []);

        [$orderLines, $totalPrice] = $this->buildMenuOrderLines(
            $selectedItems,
            $menuItems,
            $variantIdsByItem,
            $addonIdsByItem
        );

        $menuItemsSummary = $this->buildMenuPreOrderSummary($orderLines);

        $preOrder = DB::transaction(function () use ($validated, $totalPrice, $menuItemsSummary): PreOrder {
            $phone = trim((string) $validated['phone']);

            $customer = Customer::firstOrCreate(
                ['phone' => $phone],
                ['name' => $phone]
            );

            if (trim((string) $customer->name) === '') {
                $customer->name = $phone;
                $customer->save();
            }

            return PreOrder::create([
                'source_type' => 'menu',
                'source_id' => null,
                'customer_id' => $customer->id,
                'pickup_location_id' => (int) $validated['pickup_location_id'],
                'pickup_date' => $validated['pickup_date'],
                'pickup_time' => $validated['pickup_time'],
                'phone' => $phone,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'payment_status' => 'pending',
                'items_summary' => $menuItemsSummary,
            ]);
        });

        return redirect()
            ->route('preorder.menu.confirmation', ['preOrder' => $preOrder]);
    }

    public function showMenuConfirmation(PreOrder $preOrder)
    {
        abort_unless($preOrder->source_type === 'menu', 404);

        $preOrder->loadMissing([
            'pickupLocation:id,name,address',
            'customer:id,name,phone',
        ]);

        return view('pages.preorder-menu-confirmation', [
            'preOrder' => $preOrder,
        ]);
    }

    public function showCakePage()
    {
        $pickupLocations = PickupLocation::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'address']);

        $pastryItems = PastryItem::query()
            ->where('is_active', true)
            ->where('preorder_available', true)
            ->whereNotNull('price')
            ->orderBy('name')
            ->get(['id', 'name', 'description', 'price']);

        return view('pages.preorder-cake', [
            'pickupLocations' => $pickupLocations,
            'pastryItems' => $pastryItems,
        ]);
    }

    public function submitCake(Request $request)
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
            'pickup_location_id' => [
                'required',
                Rule::exists('pickup_locations', 'id')->where(
                    fn ($query) => $query->where('is_active', true)
                ),
            ],
            'pickup_date' => ['required', 'date', 'after_or_equal:today'],
            'pickup_time' => ['required', 'date_format:H:i'],
            'quantities' => ['required', 'array'],
            'quantities.*' => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);

        $selectedItems = $this->collectSelectedItems($validated['quantities']);

        if ($selectedItems->isEmpty()) {
            throw ValidationException::withMessages([
                'quantities' => 'Please select at least one pastry item quantity.',
            ]);
        }

        $pastryItems = PastryItem::query()
            ->whereIn('id', $selectedItems->pluck('id')->all())
            ->where('is_active', true)
            ->where('preorder_available', true)
            ->whereNotNull('price')
            ->get()
            ->keyBy('id');

        if ($pastryItems->count() !== $selectedItems->count()) {
            throw ValidationException::withMessages([
                'quantities' => 'One or more selected pastry items are not available for preorder.',
            ]);
        }

        $pickupSlot = $this->resolvePickupSlot(
            (string) $validated['pickup_date'],
            (string) $validated['pickup_time']
        );

        $this->ensurePickupSlotIsAvailable((int) $validated['pickup_location_id'], $pickupSlot);

        $totalPrice = $this->calculateTotalPrice($selectedItems, $pastryItems);

        $cakeItemsSummary = $this->buildCakePreOrderSummary($selectedItems, $pastryItems);

        DB::transaction(function () use ($validated, $totalPrice, $cakeItemsSummary): void {
            $phone = trim((string) $validated['phone']);

            $customer = Customer::firstOrCreate(
                ['phone' => $phone],
                ['name' => $phone]
            );

            if (trim((string) $customer->name) === '') {
                $customer->name = $phone;
                $customer->save();
            }

            PreOrder::create([
                'source_type' => 'cake',
                'source_id' => null,
                'customer_id' => $customer->id,
                'pickup_location_id' => (int) $validated['pickup_location_id'],
                'pickup_date' => $validated['pickup_date'],
                'pickup_time' => $validated['pickup_time'],
                'phone' => $phone,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'payment_status' => 'pending',
                'items_summary' => $cakeItemsSummary,
            ]);
        });

        return redirect()
            ->route('preorder.cake')
            ->with('success', 'Your cake preorder has been placed successfully.');
    }

    private function collectSelectedItems(array $quantities): Collection
    {
        return collect($quantities)
            ->map(function ($quantity, $id): array {
                return [
                    'id' => (int) $id,
                    'quantity' => (int) $quantity,
                ];
            })
            ->filter(fn (array $item): bool => $item['id'] > 0 && $item['quantity'] > 0)
            ->values();
    }

    private function calculateTotalPrice(Collection $selectedItems, Collection $itemsById): float
    {
        $totalCents = $selectedItems->sum(function (array $selectedItem) use ($itemsById): int {
            $item = $itemsById->get($selectedItem['id']);
            $priceCents = (int) round(((float) $item->price) * 100);

            return $priceCents * $selectedItem['quantity'];
        });

        return $totalCents / 100;
    }

    private function normalizeItemScalarSelections(array $values): array
    {
        $normalized = [];

        foreach ($values as $itemId => $value) {
            $normalized[(int) $itemId] = (int) $value;
        }

        return $normalized;
    }

    private function normalizeItemArraySelections(array $values): array
    {
        $normalized = [];

        foreach ($values as $itemId => $itemAddonIds) {
            if (! is_array($itemAddonIds)) {
                $normalized[(int) $itemId] = [];

                continue;
            }

            $normalized[(int) $itemId] = collect($itemAddonIds)
                ->map(fn ($addonId): int => (int) $addonId)
                ->filter(fn (int $addonId): bool => $addonId > 0)
                ->unique()
                ->values()
                ->all();
        }

        return $normalized;
    }

    private function buildMenuOrderLines(
        Collection $selectedItems,
        Collection $menuItemsById,
        array $variantIdsByItem,
        array $addonIdsByItem
    ): array {
        $orderLines = [];
        $totalCents = 0;

        foreach ($selectedItems as $selectedItem) {
            $itemId = $selectedItem['id'];
            $quantity = $selectedItem['quantity'];
            $menuItem = $menuItemsById->get($itemId);

            $selectedVariantId = (int) ($variantIdsByItem[$itemId] ?? 0);
            $selectedVariant = null;

            if ($selectedVariantId > 0) {
                $selectedVariant = $menuItem->variants->firstWhere('id', $selectedVariantId);

                if (! $selectedVariant instanceof MenuItemVariant) {
                    throw ValidationException::withMessages([
                        "variant_ids.$itemId" => "Selected variant is invalid for {$menuItem->title}.",
                    ]);
                }
            }

            $requestedAddonIds = $addonIdsByItem[$itemId] ?? [];
            $selectedAddons = $menuItem->addons->whereIn('id', $requestedAddonIds)->values();

            if ($selectedAddons->count() !== count($requestedAddonIds)) {
                throw ValidationException::withMessages([
                    "addon_ids.$itemId" => "One or more addons are invalid for {$menuItem->title}.",
                ]);
            }

            if ($selectedAddons->contains(fn ($addon): bool => ! $addon instanceof MenuItemAddon)) {
                throw ValidationException::withMessages([
                    "addon_ids.$itemId" => "One or more addons are invalid for {$menuItem->title}.",
                ]);
            }

            $unitBaseCents = (int) round(((float) ($selectedVariant?->price ?? $menuItem->price)) * 100);
            $addonsUnitCents = (int) round(
                $selectedAddons->sum(fn (MenuItemAddon $addon): float => (float) $addon->price) * 100
            );
            $lineTotalCents = ($unitBaseCents + $addonsUnitCents) * $quantity;

            $totalCents += $lineTotalCents;

            $orderLines[] = [
                'menu_item_id' => $menuItem->id,
                'menu_item_title' => $menuItem->title,
                'menu_item_image' => $menuItem->getFirstMediaUrl('menu_images'),
                'selected_variant_id' => $selectedVariant?->id,
                'selected_variant_name' => $selectedVariant?->name,
                'selected_variant_price' => $selectedVariant ? (float) $selectedVariant->price : null,
                'quantity' => $quantity,
                'price' => $unitBaseCents / 100,
                'addons_unit_price' => $addonsUnitCents / 100,
                'selected_addons' => $selectedAddons
                    ->map(fn (MenuItemAddon $addon): array => [
                        'id' => $addon->id,
                        'name' => $addon->name,
                        'price' => (float) $addon->price,
                    ])
                    ->values()
                    ->all(),
                'line_total_price' => $lineTotalCents / 100,
            ];
        }

        return [$orderLines, $totalCents / 100];
    }

    private function buildMenuPreOrderSummary(array $orderLines): array
    {
        return collect($orderLines)
            ->map(function (array $line): array {
                return [
                    'item_id' => (int) ($line['menu_item_id'] ?? 0),
                    'item_type' => 'menu',
                    'name' => (string) ($line['menu_item_title'] ?? 'Menu Item'),
                    'quantity' => (int) ($line['quantity'] ?? 0),
                    'image_url' => (string) ($line['menu_item_image'] ?? ''),
                    'variant' => $line['selected_variant_name'] ?? null,
                    'addons' => collect($line['selected_addons'] ?? [])
                        ->pluck('name')
                        ->filter()
                        ->values()
                        ->all(),
                    'line_total_price' => (float) ($line['line_total_price'] ?? 0),
                ];
            })
            ->values()
            ->all();
    }

    private function buildCakePreOrderSummary(Collection $selectedItems, Collection $pastryItems): array
    {
        return $selectedItems
            ->map(function (array $selectedItem) use ($pastryItems): array {
                /** @var PastryItem|null $pastryItem */
                $pastryItem = $pastryItems->get($selectedItem['id']);

                $unitPrice = (float) ($pastryItem?->price ?? 0);
                $quantity = (int) ($selectedItem['quantity'] ?? 0);
                $imageUrl = '';

                if ($pastryItem) {
                    $imageUrl = (string) ($pastryItem->getFirstMediaUrl('cover_image') ?: $pastryItem->getFirstMediaUrl('gallery'));
                }

                return [
                    'item_id' => (int) ($pastryItem?->id ?? 0),
                    'item_type' => 'cake',
                    'name' => (string) ($pastryItem?->name ?? 'Cake Item'),
                    'quantity' => $quantity,
                    'image_url' => $imageUrl,
                    'line_total_price' => round($unitPrice * $quantity, 2),
                ];
            })
            ->values()
            ->all();
    }

    private function resolvePickupSlot(string $pickupDate, string $pickupTime): array
    {
        $date = Carbon::createFromFormat('Y-m-d', $pickupDate);
        $time = Carbon::createFromFormat('H:i', $pickupTime);

        $hour24 = (int) $time->format('G');
        $slot = $this->mapHour24ToSlot($hour24);

        return [
            'day_of_week' => $date->dayOfWeek,
            'hour_slot' => $slot['hour_slot'],
            'period' => $slot['period'],
        ];
    }

    private function buildPickupAvailabilityMap(Collection $pickupLocations, int $daysAhead = 14): array
    {
        $today = Carbon::today();
        $availability = [];

        foreach ($pickupLocations as $location) {
            $slots = [];
            $hours = $location->hours ?? collect();

            for ($offset = 0; $offset < $daysAhead; $offset++) {
                $date = $today->copy()->addDays($offset);
                $dayOfWeek = $date->dayOfWeek;

                $daySlots = $hours
                    ->where('day_of_week', $dayOfWeek)
                    ->map(function (PickupLocationHour $hour) use ($date): ?array {
                        $hour24 = $this->mapSlotToHour24((int) $hour->hour_slot, (string) $hour->period);

                        if ($hour24 === null) {
                            return null;
                        }

                        $time = sprintf('%02d:00', $hour24);

                        return [
                            'date' => $date->format('Y-m-d'),
                            'date_label' => $date->format('D, M j'),
                            'time' => $time,
                            'time_label' => Carbon::createFromFormat('H:i', $time)->format('g:i A'),
                            'sort_key' => $hour24,
                        ];
                    })
                    ->filter()
                    ->sortBy('sort_key')
                    ->unique(fn (array $slot): string => $slot['date'].'|'.$slot['time'])
                    ->values();

                foreach ($daySlots as $slot) {
                    unset($slot['sort_key']);
                    $slots[] = $slot;
                }
            }

            $availability[(string) $location->id] = $slots;
        }

        return $availability;
    }

    private function mapHour24ToSlot(int $hour24): array
    {
        if ($hour24 >= 6 && $hour24 <= 17) {
            return [
                'hour_slot' => $hour24 - 5,
                'period' => 'day',
            ];
        }

        if ($hour24 >= 18 && $hour24 <= 23) {
            return [
                'hour_slot' => $hour24 - 17,
                'period' => 'night',
            ];
        }

        return [
            'hour_slot' => $hour24 + 7,
            'period' => 'night',
        ];
    }

    private function mapSlotToHour24(int $hourSlot, string $period): ?int
    {
        if ($hourSlot < 1 || $hourSlot > 12) {
            return null;
        }

        if ($period === 'day') {
            return $hourSlot + 5;
        }

        if ($period === 'night') {
            return $hourSlot <= 6
                ? $hourSlot + 17
                : $hourSlot - 7;
        }

        return null;
    }

    private function ensurePickupSlotIsAvailable(int $pickupLocationId, array $pickupSlot): void
    {
        $locationHoursQuery = PickupLocationHour::query()
            ->where('pickup_location_id', $pickupLocationId)
            ->where('is_active', true);

        // If no pickup hours are configured yet for this location, allow manual time selection.
        if (! $locationHoursQuery->exists()) {
            return;
        }

        $isAvailable = $locationHoursQuery
            ->where('day_of_week', $pickupSlot['day_of_week'])
            ->where('hour_slot', $pickupSlot['hour_slot'])
            ->where('period', $pickupSlot['period'])
            ->exists();

        if (! $isAvailable) {
            throw ValidationException::withMessages([
                'pickup_time' => 'Selected pickup time is not available for this location.',
            ]);
        }
    }
}
