<?php

namespace App\Filament\Resources\Loyalities\Tables;

use App\Models\Loyality;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LoyalitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reward_image_preview')
                    ->label('Image')
                    ->html()
                    ->formatStateUsing(function (Loyality $record): string {
                        $imageUrl = trim((string) $record->getFirstMediaUrl('reward_image'));

                        if ($imageUrl === '') {
                            return '<span style="color:#94a3b8;">No image</span>';
                        }

                        return '<img src="'.e($imageUrl).'" alt="'.e($record->name).'" style="width:42px;height:42px;border-radius:8px;object-fit:cover;" />';
                    }),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('points_required')
                    ->label('Points Required')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
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
