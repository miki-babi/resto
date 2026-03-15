<?php

namespace App\Filament\Resources\MenuItems\RelationManagers;

use App\Filament\Resources\MenuItems\MenuItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class AddonsRelationManager extends RelationManager
{
    protected static string $relationship = 'addons';

    // protected static ?string $relatedResource = MenuItemResource::class;



    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('name')
                    ->required(),

                Forms\Components\TextInput::make('price')
                    ->numeric(),

                Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ]);
    }
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('price'),

                Tables\Columns\TextColumn::make('sort_order'),
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
