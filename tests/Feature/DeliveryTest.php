<?php

use App\Models\Customer;
use App\Models\Delivery;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a delivery can be created and generates an order number', function () {
    $customer = Customer::create(['name' => 'John Doe', 'phone' => '1234567890']);

    $delivery = Delivery::create([
        'customer_id' => $customer->id,
        'delivery_phone' => '1234567890',
        'delivery_address' => '123 Main St',
        'delivery_date' => now(),
        'status' => 'pending',
    ]);

    expect($delivery->order_number)->toStartWith('DL-');
    expect($delivery->customer->id)->toBe($customer->id);
});

test('it suggests past addresses for a customer correctly', function () {
    $customer = Customer::create(['name' => 'Jane Smith', 'phone' => '0987654321']);

    Delivery::create([
        'customer_id' => $customer->id,
        'delivery_phone' => '0987654321',
        'delivery_address' => 'First Address',
        'delivery_date' => now(),
        'status' => 'delivered',
    ]);

    Delivery::create([
        'customer_id' => $customer->id,
        'delivery_phone' => '0987654321',
        'delivery_address' => 'Second Address',
        'delivery_date' => now(),
        'status' => 'delivered',
    ]);

    // Duplicate address should be filtered by unique() in helper
    Delivery::create([
        'customer_id' => $customer->id,
        'delivery_phone' => '0987654321',
        'delivery_address' => 'First Address',
        'delivery_date' => now(),
        'status' => 'delivered',
    ]);

    $addresses = Delivery::getPastAddressesForCustomer($customer->id);

    expect($addresses)->toHaveCount(2)
        ->and($addresses)->toContain('First Address')
        ->and($addresses)->toContain('Second Address');
});

test('it can have items and total price is calculated', function () {
    $customer = Customer::create(['name' => 'Buyer', 'phone' => '1112223333']);
    $item = MenuItem::create([
        'title' => 'Burger',
        'price' => 10.00,
        'slug' => 'burger',
    ]);

    $delivery = Delivery::create([
        'customer_id' => $customer->id,
        'delivery_phone' => '1112223333',
        'delivery_address' => 'Test Way',
        'delivery_date' => now(),
        'status' => 'pending',
        'total_price' => 10.00,
    ]);

    $delivery->items()->create([
        'menu_item_id' => $item->id,
        'menu_item_title' => $item->title,
        'quantity' => 1,
        'price' => 10.00,
        'line_total_price' => 10.00,
    ]);

    expect($delivery->items)->toHaveCount(1)
        ->and($delivery->items->first()->menu_item_title)->toBe('Burger');
});

test('it fills delivery item snapshots when only item id quantity and price are provided', function () {
    $customer = Customer::create(['name' => 'Snapshot Buyer', 'phone' => '2223334444']);
    $category = MenuCategory::factory()->create();
    $item = MenuItem::create([
        'menu_category_id' => $category->id,
        'title' => 'Pizza',
        'price' => 15.00,
        'slug' => 'pizza',
    ]);

    $delivery = Delivery::create([
        'customer_id' => $customer->id,
        'delivery_phone' => '2223334444',
        'delivery_address' => 'Snapshot Street',
        'delivery_date' => now(),
        'status' => 'pending',
        'total_price' => 30.00,
    ]);

    $deliveryItem = $delivery->items()->create([
        'menu_item_id' => $item->id,
        'quantity' => 2,
        'price' => 15.00,
    ]);

    expect($deliveryItem->menu_item_title)->toBe('Pizza')
        ->and((float) $deliveryItem->line_total_price)->toBe(30.00);
});
