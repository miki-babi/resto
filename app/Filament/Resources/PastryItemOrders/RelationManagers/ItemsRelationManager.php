<?php

namespace App\Filament\Resources\PastryItemOrders\RelationManagers;

use App\Filament\Resources\PastryItemOrders\PastryItemOrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;


use Filament\Tables;
// use Filament\Tables\Table;
use Filament\Forms;
use Filament\Forms\Form;
// use Filament\Actions\CreateAction;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $relatedResource = PastryItemOrderResource::class;

    
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('pastry_item_id')
    ->relationship('item', 'name')
    ->searchable()
    ->required(),

TextInput::make('quantity')
    ->numeric()
    ->default(1),

TextInput::make('price')
    ->numeric()
    ->required()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->numeric(),

                Tables\Columns\TextColumn::make('price')
                    ->numeric(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
