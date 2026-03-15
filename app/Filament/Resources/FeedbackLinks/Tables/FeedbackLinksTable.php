<?php

namespace App\Filament\Resources\FeedbackLinks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class FeedbackLinksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('address')
                    ->searchable(),
                TextColumn::make('google_review_link')
                    ->searchable(),
                    TextColumn::make('feedback_url')
    ->label('Feedback URL')
    ->url(fn ($record) => $record->feedback_url) // makes it clickable
    ->copyable()
    ->openUrlInNewTab(), // optional: open in new tab
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
