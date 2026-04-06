<?php

use App\Models\Customer;
use App\Models\Loyality;
use App\Models\LoyalityRedemption;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\PastryItem;
use App\Models\PreOrder;
use App\Services\LoyaltyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

it('credits expected menu points when preorder becomes completed', function () {
    $customer = createCustomer(phone: '0990000001');
    $menuItem = createMenuItem(points: 12);

    $preOrder = createPreOrder([
        'customer_id' => $customer->id,
        'phone' => $customer->phone,
        'status' => 'pending',
        'items_summary' => [
            [
                'item_id' => $menuItem->id,
                'item_type' => 'menu',
                'quantity' => 3,
                'name' => $menuItem->title,
            ],
        ],
    ]);

    expect($customer->fresh()->loyalty_points_balance)->toBe(0);
    expect((bool) $preOrder->fresh()->loyalty_points_applied)->toBeFalse();

    $preOrder->update(['status' => 'completed']);

    expect($customer->fresh()->loyalty_points_balance)->toBe(36);
    expect((int) $preOrder->fresh()->loyalty_points_earned)->toBe(36);
    expect((bool) $preOrder->fresh()->loyalty_points_applied)->toBeTrue();
});

it('credits expected cake points when preorder is completed', function () {
    $customer = createCustomer(phone: '0990000002');
    $pastryItem = createPastryItem(points: 7);

    $preOrder = createPreOrder([
        'source_type' => 'cake',
        'customer_id' => $customer->id,
        'phone' => $customer->phone,
        'status' => 'completed',
        'items_summary' => [
            [
                'item_id' => $pastryItem->id,
                'item_type' => 'cake',
                'quantity' => 4,
                'name' => $pastryItem->name,
            ],
        ],
    ]);

    expect($customer->fresh()->loyalty_points_balance)->toBe(28);
    expect((int) $preOrder->fresh()->loyalty_points_earned)->toBe(28);
    expect((bool) $preOrder->fresh()->loyalty_points_applied)->toBeTrue();
});

it('does not credit points for non-completed preorder', function () {
    $customer = createCustomer(phone: '0990000003');
    $menuItem = createMenuItem(points: 15);

    $preOrder = createPreOrder([
        'customer_id' => $customer->id,
        'phone' => $customer->phone,
        'status' => 'pending',
        'items_summary' => [
            [
                'item_id' => $menuItem->id,
                'item_type' => 'menu',
                'quantity' => 2,
            ],
        ],
    ]);

    expect($customer->fresh()->loyalty_points_balance)->toBe(0);
    expect((int) $preOrder->fresh()->loyalty_points_earned)->toBe(0);
    expect((bool) $preOrder->fresh()->loyalty_points_applied)->toBeFalse();
});

it('reverses credited points when completed preorder moves out of completed status', function () {
    $customer = createCustomer(phone: '0990000004');
    $menuItem = createMenuItem(points: 10);

    $preOrder = createPreOrder([
        'customer_id' => $customer->id,
        'phone' => $customer->phone,
        'status' => 'completed',
        'items_summary' => [
            [
                'item_id' => $menuItem->id,
                'item_type' => 'menu',
                'quantity' => 2,
            ],
        ],
    ]);

    expect($customer->fresh()->loyalty_points_balance)->toBe(20);

    $preOrder->update(['status' => 'cancelled']);

    expect($customer->fresh()->loyalty_points_balance)->toBe(0);
    expect((int) $preOrder->fresh()->loyalty_points_earned)->toBe(0);
    expect((bool) $preOrder->fresh()->loyalty_points_applied)->toBeFalse();
});

it('reapplies points once after reversal and remains idempotent', function () {
    $customer = createCustomer(phone: '0990000005');
    $menuItem = createMenuItem(points: 8);

    $preOrder = createPreOrder([
        'customer_id' => $customer->id,
        'phone' => $customer->phone,
        'status' => 'pending',
        'items_summary' => [
            [
                'item_id' => $menuItem->id,
                'item_type' => 'menu',
                'quantity' => 5,
            ],
        ],
    ]);

    $preOrder->update(['status' => 'completed']);
    expect($customer->fresh()->loyalty_points_balance)->toBe(40);

    $preOrder->update(['status' => 'cancelled']);
    expect($customer->fresh()->loyalty_points_balance)->toBe(0);

    $preOrder->update(['status' => 'completed']);
    expect($customer->fresh()->loyalty_points_balance)->toBe(40);

    $preOrder->update(['status' => 'completed']);
    expect($customer->fresh()->loyalty_points_balance)->toBe(40);
});

it('allows negative balance when reversing previously spent points', function () {
    $customer = createCustomer(phone: '0990000006');
    $menuItem = createMenuItem(points: 10);

    $preOrder = createPreOrder([
        'customer_id' => $customer->id,
        'phone' => $customer->phone,
        'status' => 'completed',
        'items_summary' => [
            [
                'item_id' => $menuItem->id,
                'item_type' => 'menu',
                'quantity' => 1,
            ],
        ],
    ]);

    $reward = createReward(pointsRequired: 10);

    app(LoyaltyService::class)->redeemReward($customer->fresh(), $reward);
    expect($customer->fresh()->loyalty_points_balance)->toBe(0);

    $preOrder->update(['status' => 'cancelled']);
    expect($customer->fresh()->loyalty_points_balance)->toBe(-10);
});

it('redeems a reward and stores redemption snapshot', function () {
    $customer = createCustomer(phone: '0990000007', balance: 50);
    $reward = createReward(pointsRequired: 20);

    $redemption = app(LoyaltyService::class)->redeemReward(
        customer: $customer,
        loyality: $reward,
        preOrder: null,
        notes: 'Front desk redemption',
    );

    expect($customer->fresh()->loyalty_points_balance)->toBe(30);
    expect($redemption->points_spent)->toBe(20);
    expect($redemption->loyality_id)->toBe($reward->id);
    expect($redemption->pre_order_id)->toBeNull();
    expect($redemption->notes)->toBe('Front desk redemption');
    expect(LoyalityRedemption::count())->toBe(1);
});

it('fails redemption when customer has insufficient points', function () {
    $customer = createCustomer(phone: '0990000008', balance: 5);
    $reward = createReward(pointsRequired: 25);

    expect(fn () => app(LoyaltyService::class)->redeemReward($customer, $reward))
        ->toThrow(ValidationException::class);

    expect($customer->fresh()->loyalty_points_balance)->toBe(5);
    expect(LoyalityRedemption::count())->toBe(0);
});

it('allows redeeming the same reward multiple times', function () {
    $customer = createCustomer(phone: '0990000009', balance: 100);
    $reward = createReward(pointsRequired: 30);

    app(LoyaltyService::class)->redeemReward($customer, $reward);
    app(LoyaltyService::class)->redeemReward($customer->fresh(), $reward);

    expect($customer->fresh()->loyalty_points_balance)->toBe(40);
    expect(LoyalityRedemption::where('loyality_id', $reward->id)->count())->toBe(2);
});

it('supports redemption with and without preorder association', function () {
    $customer = createCustomer(phone: '0990000010', balance: 80);
    $reward = createReward(pointsRequired: 20);

    $preOrder = createPreOrder([
        'customer_id' => $customer->id,
        'phone' => $customer->phone,
        'status' => 'pending',
        'items_summary' => [],
    ]);

    $service = app(LoyaltyService::class);

    $withOrder = $service->redeemReward($customer, $reward, $preOrder, 'Applied on order');
    $withoutOrder = $service->redeemReward($customer->fresh(), $reward, null, 'Manual');

    expect($withOrder->pre_order_id)->toBe($preOrder->id);
    expect($withoutOrder->pre_order_id)->toBeNull();
    expect($withOrder->customer_id)->toBe($customer->id);
    expect($withoutOrder->customer_id)->toBe($customer->id);
});

it('keeps phone-based customer auto-linking for preorders', function () {
    expect(Customer::count())->toBe(0);

    $preOrder = createPreOrder([
        'customer_id' => null,
        'phone' => '0999000011',
        'status' => 'pending',
        'items_summary' => [],
    ]);

    expect(Customer::count())->toBe(1);
    expect($preOrder->fresh()->customer_id)->not()->toBeNull();
    expect($preOrder->fresh()->customer?->phone)->toBe('0999000011');
});

function createCustomer(string $phone, int $balance = 0): Customer
{
    return Customer::create([
        'name' => 'Customer '.$phone,
        'phone' => $phone,
        'is_blocked' => false,
        'loyalty_points_balance' => $balance,
    ]);
}

function createMenuItem(int $points): MenuItem
{
    $category = MenuCategory::create([
        'name' => 'Category '.Str::random(6),
        'slug' => 'category-'.Str::lower(Str::random(10)),
        'description' => null,
        'is_active' => true,
        'sort_order' => 0,
    ]);

    return MenuItem::create([
        'menu_category_id' => $category->id,
        'title' => 'Menu Item '.Str::random(6),
        'slug' => 'menu-item-'.Str::lower(Str::random(10)),
        'description' => null,
        'price' => 10,
        'loyalty_points' => $points,
        'is_available' => true,
        'preorder_available' => true,
        'is_featured' => false,
        'sort_order' => 0,
    ]);
}

function createPastryItem(int $points): PastryItem
{
    return PastryItem::create([
        'name' => 'Pastry '.Str::random(6),
        'description' => null,
        'price' => 25,
        'loyalty_points' => $points,
        'is_active' => true,
        'preorder_available' => true,
    ]);
}

function createReward(int $pointsRequired): Loyality
{
    return Loyality::create([
        'name' => 'Reward '.Str::random(6),
        'description' => null,
        'points_required' => $pointsRequired,
        'is_active' => true,
        'sort_order' => 0,
    ]);
}

function createPreOrder(array $attributes): PreOrder
{
    return PreOrder::create(array_merge([
        'source_type' => 'menu',
        'source_id' => null,
        'customer_id' => null,
        'phone' => '0991000000',
        'pickup_location_id' => null,
        'pickup_date' => now()->toDateString(),
        'pickup_time' => '10:00',
        'total_price' => 20,
        'status' => 'pending',
        'payment_status' => 'pending',
        'items_summary' => [],
    ], $attributes));
}
