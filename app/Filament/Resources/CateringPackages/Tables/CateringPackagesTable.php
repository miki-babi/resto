<?php

namespace App\Filament\Resources\CateringPackages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CateringPackagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('min_guests')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price_per_person')
                    ->label('Per Person')
                    ->formatStateUsing(fn ($state) => $state === null ? '—' : number_format((float) $state, 0))
                    ->sortable(),
                TextColumn::make('price_total')
                    ->label('Package')
                    ->formatStateUsing(fn ($state) => $state === null ? '—' : number_format((float) $state, 0))
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean(),
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
