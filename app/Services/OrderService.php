<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Delivery;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\MenuItemAddon;
use App\Models\MenuItemVariant;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function createDelivery(array $data): Delivery
    {
        return DB::transaction(function () use ($data) {
            // 1. Normalize Inputs
            $itemQuantities = $this->normalizeItemScalarSelections($data['quantities']);
            $itemVariants = $this->normalizeItemScalarSelections($data['variants'] ?? []);
            $itemAddons = $this->normalizeItemArraySelections($data['addons'] ?? []);

            // 2. Fetch required models (Moved from Controller)
            $menuItemIds = array_keys(array_filter($itemQuantities, fn ($q) => $q > 0));

            if (empty($menuItemIds)) {
                throw ValidationException::withMessages(['quantities' => 'Please select at least one item.']);
            }

            $menuItems = MenuItem::whereIn('id', $menuItemIds)->where('is_available', true)->get()->keyBy('id');

            $selectedItems = collect($itemQuantities)
                ->map(fn ($q, $id) => ['id' => $id, 'quantity' => $q])
                ->filter(fn ($i) => $i['quantity'] > 0)
                ->values();

            // 3. Use existing logic to build lines
            [$orderLines, $totalPrice] = $this->buildMenuOrderLines(
                $selectedItems,
                $menuItems,
                $itemVariants,
                $itemAddons
            );

            // 4. Persistence
            $phone = trim((string) $data['phone']);
            $customer = Customer::firstOrCreate(['phone' => $phone], ['name' => $phone]);

            $delivery = Delivery::create([
                'customer_id' => $customer->id,
                'delivery_phone' => $phone,
                'delivery_address' => $data['delivery_address'],
                'delivery_date' => Carbon::today(),
                'total_price' => $totalPrice,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            foreach ($orderLines as $line) {
                $delivery->items()->create($line);
            }

            return $delivery;
        });
    }

    public function getDeliveryMenuData(): array
    {
        $categories = MenuCategory::activeForDelivery()
            ->with([
                'items' => fn ($q) => $q->availableForPreorder()->with([
                    'variants' => fn ($v) => $v->orderBy('sort_order')->orderBy('name'),
                    'addons' => fn ($a) => $a->where('is_active', true)->orderBy('sort_order')->orderBy('name'),
                    'media',
                ]),
            ])
            ->get()
            ->filter(fn ($cat) => $cat->items->isNotEmpty())
            ->values();

        $catalog = $categories->flatMap->items
            ->unique('id')
            ->map(fn (MenuItem $item) => $this->formatMenuItemForCatalog($item))
            ->values()
            ->all();

        return [
            'menuCategories' => $categories,
            'menuItemsCatalog' => $catalog,
        ];
    }

    /**
     * Keep the transformation logic separate.
     * You could also use an Eloquent Resource class here.
     */
    protected function formatMenuItemForCatalog(MenuItem $item): array
    {
        return [
            'id' => (int) $item->id,
            'title' => (string) $item->title,
            'price' => (float) $item->price,
            'image_url' => (string) ($item->getFirstMediaUrl('menu_images') ?: ''),
            'variants' => $item->variants->map(fn ($v) => [
                'id' => (int) $v->id,
                'name' => (string) $v->name,
                'price' => (float) $v->price,
            ])->values()->all(),
            'addons' => $item->addons->map(fn ($a) => [
                'id' => (int) $a->id,
                'name' => (string) $a->name,
                'price' => (float) $a->price,
            ])->values()->all(),
        ];
    }

    /**
     * Keep the normalization logic here to keep the controller clean.
     */
    protected function normalizeItemScalarSelections(array $values): array
    {
        $normalized = [];
        foreach ($values as $itemId => $value) {
            $normalized[(int) $itemId] = (int) $value;
        }

        return $normalized;
    }

    protected function normalizeItemArraySelections(array $values): array
    {
        $normalized = [];
        foreach ($values as $itemId => $itemAddonIds) {
            $normalized[(int) $itemId] = is_array($itemAddonIds)
                ? collect($itemAddonIds)->map(fn ($id) => (int) $id)->filter(fn ($id) => $id > 0)->unique()->values()->all()
                : [];
        }

        return $normalized;
    }

    /**
     * Build order lines with pricing snapshots.
     *
     * @param  Collection<int, array{id: int, quantity: int}>  $selectedItems
     * @param  Collection<int, MenuItem>  $menuItemsById
     * @param  array<int, int>  $variantIdsByItem
     * @param  array<int, array<int>>  $addonIdsByItem
     * @return array{0: array, 1: float}
     *
     * @throws ValidationException
     */
    public function buildMenuOrderLines(
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

            if (! $menuItem) {
                continue;
            }

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

            $unitBaseCents = (int) round(((float) ($selectedVariant?->price ?? $menuItem->price)) * 100);
            $addonsUnitCents = (int) round(
                $selectedAddons->sum(fn (MenuItemAddon $addon): float => (float) $addon->price) * 100
            );
            $lineTotalCents = ($unitBaseCents + $addonsUnitCents) * $quantity;

            $totalCents += $lineTotalCents;

            $orderLines[] = [
                'menu_item_id' => $menuItem->id,
                'menu_item_title' => $menuItem->title,
                'menu_item_image' => (string) ($menuItem->getFirstMediaUrl('menu_images') ?: ''),
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

    /**
     * Build a JSON-serializable summary for PreOrders.
     */
    public function buildMenuPreOrderSummary(array $orderLines): array
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
}
