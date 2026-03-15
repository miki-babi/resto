<?php

namespace App\Filament\Resources\MenuItems\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class MenuItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                
        Select::make('menu_category_id')
            ->relationship('category', 'name')
            ->required(),

        TextInput::make('title')
            ->required(),

        TextInput::make('slug')
            ->required(),

        Textarea::make('description'),

        TextInput::make('price')
            ->numeric(),

        Toggle::make('is_available')
            ->default(true),

        Toggle::make('is_featured'),

        TextInput::make('sort_order')
            ->numeric()
            ->default(0),

        SpatieMediaLibraryFileUpload::make('menu_images')
            ->collection('menu_images')
            ->multiple()
            ->image(),
            ]);
    }
}
