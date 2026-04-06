<?php

namespace App\Filament\Resources\MealBoxSubscriptions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MealBoxSubscriptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name') // Accesses the 'name' column on the 'customer' relationship
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('mealBoxPlan.name') // Accesses the 'name' column on the 'mealBoxPlan' relationship
                    ->label('Plan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)

                    ->sortable(),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                // TextColumn::make('delivery_time')
                //     ->time()
                //     ->sortable(),
                TextColumn::make('delivery_time')
                    ->label('Delivery Windows')
                    ->badge() // Turns the times into nice little bubbles
                    ->separator(',') // Tells Filament to treat each array item as a separate badge
                    ->color('info')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '06:00' => '12:00 morning',  // 6 AM = 1 Ethiopian hour
                        '07:00' => '1:00 morning',
                        '08:00' => '2:00 morning',
                        '09:00' => '3:00 morning',
                        '10:00' => '4:00 morning',
                        '11:00' => '5:00 morning',
                        '12:00' => '6:00 afternoon', // 12 PM = 6 Ethiopian hour
                        '13:00' => '7:00 afternoon',
                        '14:00' => '8:00 afternoon',
                        '15:00' => '9:00 afternoon',
                        '16:00' => '10:00 afternoon',
                        '17:00' => '11:00 afternoon',
                        '18:00' => '12:00 evening',  // 6 PM = 12 Ethiopian hour
                        '19:00' => '1:00 evening', // optional if you want PM suffix
                    }),
                TextColumn::make('address')
                    ->searchable(),
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
