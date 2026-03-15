<?php

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\MenuItemAddon;
use App\Models\MenuItemOrder;
use App\Models\MenuItemOrderItem;
use App\Models\MenuItemOrderItemAddon;
use App\Models\MenuItemVariant;
use App\Models\PickupLocation;
use App\Models\PickupLocationHour;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

it('creates a menu item order with variant and addons', function () {
    $location = PickupLocation::create([
        'name' => 'Bole Cafe',
        'address' => 'Bole',
        'is_active' => true,
    ]);

    $hour = PickupLocationHour::create([
        'pickup_location_id' => $location->id,
        'day_of_week' => 0,
        'hour_slot' => 2,
        'period' => 'day',
        'is_active' => true,
    ]);

    $category = MenuCategory::create([
        'name' => 'Coffee',
        'slug' => 'coffee',
        'description' => null,
        'is_active' => true,
        'sort_order' => 0,
    ]);

    $item = MenuItem::create([
        'menu_category_id' => $category->id,
        'title' => 'Latte',
        'slug' => 'latte',
        'description' => 'Milk coffee',
        'price' => 10.00,
        'is_available' => true,
        'is_featured' => false,
        'sort_order' => 0,
    ]);

    $variant = MenuItemVariant::create([
        'menu_item_id' => $item->id,
        'name' => 'Large',
        'price' => 12.00,
        'sort_order' => 0,
    ]);

    $addon = MenuItemAddon::create([
        'menu_item_id' => $item->id,
        'name' => 'Extra Shot',
        'price' => 1.50,
        'sort_order' => 0,
        'is_active' => true,
    ]);

    $payload = [
        'pickup_location_id' => $location->id,
        'pickup_day_of_week' => $hour->day_of_week,
        'pickup_hour_slot' => $hour->hour_slot,
        'pickup_period' => $hour->period,
        'items' => [
            [
                'menu_item_id' => $item->id,
                'menu_item_variant_id' => $variant->id,
                'quantity' => 2,
                'addon_ids' => [$addon->id],
            ],
        ],
    ];

    $response = $this->postJson(route('order.store'), $payload);

    $response
        ->assertStatus(201)
        ->assertJsonStructure(['redirect', 'order_id']);

    expect(MenuItemOrder::count())->toBe(1);
    expect(MenuItemOrderItem::count())->toBe(1);
    expect(MenuItemOrderItemAddon::count())->toBe(1);

    $order = MenuItemOrder::firstOrFail();
    expect($order->status)->toBe('pending');
    expect($order->pickup_location_id)->toBe($location->id);

    $expectedTotal = round((12.00 + 1.50) * 2, 2);
    expect((float) $order->total_price)->toBe($expectedTotal);

    $orderItem = MenuItemOrderItem::firstOrFail();
    expect($orderItem->title)->toBe('Latte');
    expect($orderItem->variant_name)->toBe('Large');
    expect((float) $orderItem->unit_price)->toBe(12.00);
    expect($orderItem->quantity)->toBe(2);
});

it('rejects addons that do not belong to the selected item', function () {
    $location = PickupLocation::create([
        'name' => 'Bole Cafe',
        'address' => 'Bole',
        'is_active' => true,
    ]);

    PickupLocationHour::create([
        'pickup_location_id' => $location->id,
        'day_of_week' => 0,
        'hour_slot' => 2,
        'period' => 'day',
        'is_active' => true,
    ]);

    $category = MenuCategory::create([
        'name' => 'Coffee',
        'slug' => 'coffee',
        'description' => null,
        'is_active' => true,
        'sort_order' => 0,
    ]);

    $itemA = MenuItem::create([
        'menu_category_id' => $category->id,
        'title' => 'Latte',
        'slug' => 'latte',
        'description' => null,
        'price' => 10.00,
        'is_available' => true,
        'is_featured' => false,
        'sort_order' => 0,
    ]);

    $itemB = MenuItem::create([
        'menu_category_id' => $category->id,
        'title' => 'Espresso',
        'slug' => 'espresso',
        'description' => null,
        'price' => 8.00,
        'is_available' => true,
        'is_featured' => false,
        'sort_order' => 0,
    ]);

    $foreignAddon = MenuItemAddon::create([
        'menu_item_id' => $itemB->id,
        'name' => 'Foreign Addon',
        'price' => 2.00,
        'sort_order' => 0,
        'is_active' => true,
    ]);

    $payload = [
        'pickup_location_id' => $location->id,
        'pickup_day_of_week' => 0,
        'pickup_hour_slot' => 2,
        'pickup_period' => 'day',
        'items' => [
            [
                'menu_item_id' => $itemA->id,
                'quantity' => 1,
                'addon_ids' => [$foreignAddon->id],
            ],
        ],
    ];

    $this->postJson(route('order.store'), $payload)
        ->assertStatus(422)
        ->assertJsonFragment(['message' => 'Selected addons are not valid for one or more items.']);

    expect(MenuItemOrder::count())->toBe(0);
});

it('rejects pickup time that is not active for the location', function () {
    $location = PickupLocation::create([
        'name' => 'Bole Cafe',
        'address' => 'Bole',
        'is_active' => true,
    ]);

    PickupLocationHour::create([
        'pickup_location_id' => $location->id,
        'day_of_week' => 0,
        'hour_slot' => 2,
        'period' => 'day',
        'is_active' => true,
    ]);

    $category = MenuCategory::create([
        'name' => 'Coffee',
        'slug' => 'coffee',
        'description' => null,
        'is_active' => true,
        'sort_order' => 0,
    ]);

    $item = MenuItem::create([
        'menu_category_id' => $category->id,
        'title' => 'Latte',
        'slug' => 'latte',
        'description' => null,
        'price' => 10.00,
        'is_available' => true,
        'is_featured' => false,
        'sort_order' => 0,
    ]);

    $payload = [
        'pickup_location_id' => $location->id,
        'pickup_day_of_week' => 0,
        'pickup_hour_slot' => 5, // not in pickup_location_hours
        'pickup_period' => 'day',
        'items' => [
            [
                'menu_item_id' => $item->id,
                'quantity' => 1,
            ],
        ],
    ];

    $this->postJson(route('order.store'), $payload)
        ->assertStatus(422)
        ->assertJsonFragment(['message' => 'Selected pickup time is not available for this location.']);
});

it('requires auth for staff screens and actions', function () {
    $this->get(route('staff.index'))
        ->assertStatus(302)
        ->assertRedirect(route('login'));
});

it('enforces staff status transitions', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $location = PickupLocation::create([
        'name' => 'Bole Cafe',
        'address' => 'Bole',
        'is_active' => true,
    ]);

    $order = MenuItemOrder::create([
        'public_token' => (string) Str::uuid(),
        'pickup_location_id' => $location->id,
        'pickup_day_of_week' => 0,
        'pickup_hour_slot' => 2,
        'pickup_period' => 'day',
        'total_price' => 10.00,
        'status' => 'pending',
    ]);

    $this->postJson(route('staff.orders.ready', $order))
        ->assertStatus(422);

    $this->postJson(route('staff.orders.accept', $order))
        ->assertOk();

    $order->refresh();
    expect($order->status)->toBe('preparing');

    $this->postJson(route('staff.orders.ready', $order))
        ->assertOk();

    $order->refresh();
    expect($order->status)->toBe('ready');

    $this->postJson(route('staff.orders.picked_up', $order))
        ->assertOk();

    $order->refresh();
    expect($order->status)->toBe('completed');
});

it('polls staff orders scoped to a pickup location', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $locA = PickupLocation::create([
        'name' => 'A',
        'address' => null,
        'is_active' => true,
    ]);

    $locB = PickupLocation::create([
        'name' => 'B',
        'address' => null,
        'is_active' => true,
    ]);

    $orderA = MenuItemOrder::create([
        'public_token' => (string) Str::uuid(),
        'pickup_location_id' => $locA->id,
        'pickup_day_of_week' => 0,
        'pickup_hour_slot' => 2,
        'pickup_period' => 'day',
        'total_price' => 10.00,
        'status' => 'pending',
    ]);

    MenuItemOrder::create([
        'public_token' => (string) Str::uuid(),
        'pickup_location_id' => $locB->id,
        'pickup_day_of_week' => 0,
        'pickup_hour_slot' => 2,
        'pickup_period' => 'day',
        'total_price' => 10.00,
        'status' => 'pending',
    ]);

    $this->getJson(route('staff.command.poll', $locA))
        ->assertOk()
        ->assertJsonPath('pending_ids', [$orderA->id]);
});
