<?php

namespace App\Filament\Resources\PreOrders\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class PreOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_number')
                    ->label('Order #')
                    ->disabled(),
                Select::make('source_type')
                    ->options([
                        'menu' => 'Menu',
                        'cake' => 'Cake',
                    ])
                    ->disabled(),
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                Select::make('pickup_location_id')
                    ->relationship('pickupLocation', 'name')
                    ->searchable()
                    ->preload()
                    ->default(null),
                DatePicker::make('pickup_date')
                    ->default(null),
                TimePicker::make('pickup_time')
                    ->seconds(false)
                    ->default(null),
                TextInput::make('total_price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('loyalty_points_earned')
                    ->label('Loyalty Points Earned')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false),
                Select::make('loyalty_points_applied')
                    ->label('Loyalty Points Applied')
                    ->options([
                        1 => 'Yes',
                        0 => 'No',
                    ])
                    ->disabled()
                    ->dehydrated(false),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'preparing' => 'Preparing',
                        'ready' => 'Ready',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),
                Select::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ])
                    ->required(),
            ]);
    }
}
