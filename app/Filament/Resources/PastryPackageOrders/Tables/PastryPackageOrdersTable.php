<?php

namespace App\Filament\Resources\PastryPackageOrders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PastryPackageOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pastry_customer_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('pastry_package_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_price')
                    ->money()
                    ->sortable(),
                TextColumn::make('order_type')
                    ->badge(),
                TextColumn::make('pickup_location_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('pickup_day_of_week')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('pickup_hour_slot')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('pickup_period')
                    ->badge(),
                TextColumn::make('delivery_phone')
                    ->searchable(),
                TextColumn::make('delivery_address')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('payment_status')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
