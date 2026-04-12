<?php

namespace App\Filament\Resources\Deliveries\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DeliveryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_number')
                    ->label('Order #')
                    ->disabled()
                    ->dehydrated(false),

                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->required(),

                TextInput::make('delivery_phone')
                    ->tel()
                    ->required(),

                Textarea::make('delivery_address')
                    ->required(),

                DatePicker::make('delivery_date')
                    ->default(now())
                    ->required(),

                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'preparing' => 'Preparing',
                        'out_for_delivery' => 'Out for Delivery',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('pending'),

                Select::make('payment_status')
                    ->options([
                        'unpaid' => 'Unpaid',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                    ])
                    ->required()
                    ->default('unpaid'),

                Repeater::make('items')
                    ->relationship()
                    ->schema([
                        Select::make('menu_item_id')
                            ->relationship('item', 'title')
                            ->required(),

                        TextInput::make('quantity')
                            ->numeric()
                            ->default(1)
                            ->required(),

                        TextInput::make('price')
                            ->numeric()
                            ->prefix('$')
                            ->required(),
                    ])
                    ->columns(3),

                TextInput::make('total_price')
                    ->numeric()
                    ->prefix('$')
                    ->required(),
            ]);
    }
}
