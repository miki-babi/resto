<?php

namespace App\Filament\Resources\PastryPackages\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->required()
                    ->default(1),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->nullable(),
                Forms\Components\Toggle::make('show_price')
                    ->default(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pivot.amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pivot.price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('pivot.show_price')
                    ->boolean()
                    ->sortable(),
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(fn (Builder $query) => $query->orderBy('name'))
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->searchable(),
                        Forms\Components\TextInput::make('amount')
                            ->numeric()
                            ->required()
                            ->default(1),
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->nullable(),
                        Forms\Components\Toggle::make('show_price')
                            ->default(false),
                    ]),
            ])
            ->actions([
                EditAction::make(),
                DetachAction::make(),
            ]);
    }
}
