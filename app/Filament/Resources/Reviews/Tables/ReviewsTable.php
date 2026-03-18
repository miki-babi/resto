<?php

namespace App\Filament\Resources\Reviews\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class ReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                //
                ImageColumn::make('avatar')
            ->label('Avatar')
            ->getStateUsing(fn($record) => $record->getFirstMediaUrl('avatar')),

        TextColumn::make('reviewer_name')
            ->label('Name')
            ->searchable()
            ->sortable(),

        TextColumn::make('stars')
            ->label('Stars')
            ->sortable(),

        IconColumn::make('is_featured')
            ->label('Featured')
            ->boolean(),

        TextColumn::make('sort_order')
            ->label('Order')
            ->sortable(),

        TextColumn::make('created_at')
            ->label('Created')
            ->dateTime()
            ->sortable(),
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
