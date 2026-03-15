<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\MenuItemAddon;
use App\Models\MenuItemOrder;
use App\Models\MenuItemOrderItem;
use App\Models\PickupLocation;
use App\Models\PickupLocationHour;
use App\Support\PickupTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $categories = MenuCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->with([
                'items' => function ($query) {
                    $query
                        ->where('is_available', true)
                        ->orderBy('sort_order')
                        ->orderBy('title');
                },
                'items.variants' => function ($query) {
                    $query->orderBy('sort_order')->orderBy('name');
                },
                'items.addons' => function ($query) {
                    $query->where('is_active', true)->orderBy('sort_order')->orderBy('name');
                },
            ])
            ->get();

        $locations = PickupLocation::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->with(['hours' => function ($query) {
                $query->where('is_active', true);
            }])
            ->get();

        $categoriesData = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'items' => $category->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'description' => $item->description,
                        'price' => (float) $item->price,
                        'variants' => $item->variants->map(fn ($variant) => [
                            'id' => $variant->id,
                            'name' => $variant->name,
                            'price' => (float) $variant->price,
                        ])->values(),
                        'addons' => $item->addons->map(fn ($addon) => [
                            'id' => $addon->id,
                            'name' => $addon->name,
                            'price' => (float) $addon->price,
                        ])->values(),
                    ];
                })->values(),
            ];
        })->values();

        $locationsData = $locations->map(fn ($location) => [
            'id' => $location->id,
            'name' => $location->name,
            'address' => $location->address,
        ])->values();

        $pickupOptionsByLocation = [];
        $now = now();
        $rangeEnd = $now->copy()->addDays(7);

        foreach ($locations as $location) {
            $options = [];

            foreach ($location->hours as $hour) {
                $pickupAt = PickupTime::nextOccurrence(
                    from: $now->copy(),
                    dayOfWeek: (int) $hour->day_of_week,
                    hourSlot: (int) $hour->hour_slot,
                    period: (string) $hour->period,
                );

                if ($pickupAt->greaterThan($rangeEnd)) {
                    continue;
                }

                $dateKey = $pickupAt->toDateString();
                $options[$dateKey] ??= [
                    'date_key' => $dateKey,
                    'date_label' => $pickupAt->format('D, M j'),
                    'options' => [],
                ];

                $options[$dateKey]['options'][] = [
                    'day_of_week' => (int) $hour->day_of_week,
                    'hour_slot' => (int) $hour->hour_slot,
                    'period' => (string) $hour->period,
                    'pickup_at' => $pickupAt->toIso8601String(),
                    'time_label' => $pickupAt->format('g:i A'),
                    'label' => PickupTime::label($pickupAt),
                ];
            }

            $days = collect($options)
                ->sortKeys()
                ->map(function ($day) {
                    $day['options'] = collect($day['options'])
                        ->sortBy('pickup_at')
                        ->values()
                        ->all();

                    return $day;
                })
                ->values()
                ->all();

            $pickupOptionsByLocation[$location->id] = $days;
        }

        return view('pages.order.index', [
            'categories' => $categoriesData,
            'pickupLocations' => $locationsData,
            'pickupOptionsByLocation' => $pickupOptionsByLocation,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pickup_location_id' => ['required', 'integer', 'exists:pickup_locations,id'],
            'pickup_day_of_week' => ['required', 'integer', 'between:0,6'],
            'pickup_hour_slot' => ['required', 'integer', 'between:1,12'],
            'pickup_period' => ['required', 'in:day,night'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.menu_item_id' => ['required', 'integer', 'exists:menu_items,id'],
            'items.*.menu_item_variant_id' => ['nullable', 'integer'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:50'],
            'items.*.addon_ids' => ['sometimes', 'array'],
            'items.*.addon_ids.*' => ['integer', 'distinct'],
        ]);

        $pickupLocation = PickupLocation::query()
            ->whereKey($validated['pickup_location_id'])
            ->where('is_active', true)
            ->first();

        if (! $pickupLocation) {
            return response()->json([
                'message' => 'Pickup location is not available.',
            ], 422);
        }

        $hasHour = PickupLocationHour::query()
            ->where('pickup_location_id', $pickupLocation->id)
            ->where('day_of_week', $validated['pickup_day_of_week'])
            ->where('hour_slot', $validated['pickup_hour_slot'])
            ->where('period', $validated['pickup_period'])
            ->where('is_active', true)
            ->exists();

        if (! $hasHour) {
            return response()->json([
                'message' => 'Selected pickup time is not available for this location.',
            ], 422);
        }

        $menuItemIds = collect($validated['items'])
            ->pluck('menu_item_id')
            ->unique()
            ->values()
            ->all();

        $menuItems = MenuItem::query()
            ->whereIn('id', $menuItemIds)
            ->with([
                'variants' => fn ($query) => $query->orderBy('sort_order')->orderBy('name'),
                'addons' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order')->orderBy('name'),
            ])
            ->get()
            ->keyBy('id');

        $preparedItems = [];
        $total = 0.0;

        foreach ($validated['items'] as $index => $line) {
            $menuItem = $menuItems->get($line['menu_item_id']);

            if (! $menuItem || ! $menuItem->is_available) {
                return response()->json([
                    'message' => 'One or more items are not available.',
                    'error_item_index' => $index,
                ], 422);
            }

            $variantId = array_key_exists('menu_item_variant_id', $line) ? $line['menu_item_variant_id'] : null;
            $variantName = null;
            $unitPrice = (float) $menuItem->price;

            if ($menuItem->variants->isNotEmpty()) {
                if (! $variantId) {
                    return response()->json([
                        'message' => 'A variant must be selected for one or more items.',
                        'error_item_index' => $index,
                    ], 422);
                }

                $variant = $menuItem->variants->firstWhere('id', (int) $variantId);
                if (! $variant) {
                    return response()->json([
                        'message' => 'Selected variant is not valid for one or more items.',
                        'error_item_index' => $index,
                    ], 422);
                }

                $variantId = $variant->id;
                $variantName = $variant->name;
                $unitPrice = (float) $variant->price;
            } elseif ($variantId) {
                return response()->json([
                    'message' => 'Variant selection is not allowed for one or more items.',
                    'error_item_index' => $index,
                ], 422);
            }

            $addonIds = collect($line['addon_ids'] ?? [])
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values();

            $addons = $menuItem->addons->whereIn('id', $addonIds)->values();

            if ($addonIds->count() !== $addons->count()) {
                return response()->json([
                    'message' => 'Selected addons are not valid for one or more items.',
                    'error_item_index' => $index,
                ], 422);
            }

            $addonsTotal = (float) $addons->sum('price');
            $qty = (int) $line['quantity'];

            $total += ($unitPrice + $addonsTotal) * $qty;

            $preparedItems[] = [
                'menu_item_id' => $menuItem->id,
                'menu_item_variant_id' => $variantId,
                'title' => $menuItem->title,
                'variant_name' => $variantName,
                'unit_price' => $unitPrice,
                'quantity' => $qty,
                'addons' => $addons->map(fn (MenuItemAddon $addon) => [
                    'menu_item_addon_id' => $addon->id,
                    'name' => $addon->name,
                    'price' => (float) $addon->price,
                ])->all(),
            ];
        }

        $order = DB::transaction(function () use ($validated, $pickupLocation, $preparedItems, $total) {
            $order = MenuItemOrder::create([
                'public_token' => (string) Str::uuid(),
                'pickup_location_id' => $pickupLocation->id,
                'pickup_day_of_week' => (int) $validated['pickup_day_of_week'],
                'pickup_hour_slot' => (int) $validated['pickup_hour_slot'],
                'pickup_period' => (string) $validated['pickup_period'],
                'total_price' => round($total, 2),
                'status' => 'pending',
            ]);

            foreach ($preparedItems as $prepared) {
                $orderItem = MenuItemOrderItem::create([
                    'menu_item_order_id' => $order->id,
                    'menu_item_id' => $prepared['menu_item_id'],
                    'menu_item_variant_id' => $prepared['menu_item_variant_id'],
                    'title' => $prepared['title'],
                    'variant_name' => $prepared['variant_name'],
                    'unit_price' => $prepared['unit_price'],
                    'quantity' => $prepared['quantity'],
                ]);

                foreach ($prepared['addons'] as $addonSnapshot) {
                    $orderItem->addons()->create($addonSnapshot);
                }
            }

            return $order;
        });

        return response()->json([
            'redirect' => route('order.show', $order->public_token),
            'order_id' => $order->id,
        ], 201);
    }

    public function show(string $publicToken)
    {
        $order = MenuItemOrder::query()
            ->where('public_token', $publicToken)
            ->with(['pickupLocation', 'items.addons'])
            ->firstOrFail();

        return view('pages.order.show', [
            'order' => $order,
        ]);
    }

    public function poll(string $publicToken)
    {
        $order = MenuItemOrder::query()
            ->where('public_token', $publicToken)
            ->firstOrFail();

        return response()->json([
            'status' => $order->status,
            'status_label' => strtoupper($order->status),
            'updated_at' => $order->updated_at?->toIso8601String(),
        ]);
    }
}

