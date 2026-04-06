<?php

namespace App\Filament\Resources\MenuItems\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

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

                TextInput::make('price')
                    ->numeric(),
                TextInput::make('loyalty_points')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->minValue(0),

                Toggle::make('is_available')
                    ->default(true),

                Toggle::make('preorder_available')
                    ->default(false),

                Toggle::make('is_featured'),

                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
                RichEditor::make('description'),

                SpatieMediaLibraryFileUpload::make('menu_images')
                    ->collection('menu_images')
                    ->multiple()
                    ->image()
                    // ->image()
                    ->imageEditor() // Enables the editor
                    ->imageEditorAspectRatios([
                        '16:9',
                        '4:3',
                        '1:1',
                    ]),
            ]);
    }
}
