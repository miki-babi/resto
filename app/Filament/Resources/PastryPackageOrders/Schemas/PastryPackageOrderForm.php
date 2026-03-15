<?php

namespace App\Filament\Resources\PastryPackageOrders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PastryPackageOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('pastry_customer_id')
                    ->required()
                    ->numeric(),
                TextInput::make('pastry_package_id')
                    ->required()
                    ->numeric(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('total_price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Select::make('order_type')
                    ->options(['pickup' => 'Pickup', 'delivery' => 'Delivery'])
                    ->required(),
                TextInput::make('pickup_location_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('pickup_day_of_week')
                    ->numeric()
                    ->default(null),
                TextInput::make('pickup_hour_slot')
                    ->numeric()
                    ->default(null),
                Select::make('pickup_period')
                    ->options(['day' => 'Day', 'night' => 'Night'])
                    ->default(null),
                TextInput::make('delivery_phone')
                    ->tel()
                    ->default(null),
                TextInput::make('delivery_address')
                    ->default(null),
                Select::make('status')
                    ->options([
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'preparing' => 'Preparing',
            'ready' => 'Ready',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ])
                    ->default('pending')
                    ->required(),
                Select::make('payment_status')
                    ->options(['pending' => 'Pending', 'paid' => 'Paid', 'failed' => 'Failed', 'refunded' => 'Refunded'])
                    ->default('pending')
                    ->required(),
            ]);
    }
}
