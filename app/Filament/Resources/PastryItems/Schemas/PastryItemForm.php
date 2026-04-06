<?php

namespace App\Filament\Resources\PastryItems\Schemas;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PastryItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
                Textarea::make('description'),
                TextInput::make('price')->numeric(),
                TextInput::make('loyalty_points')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->minValue(0),
                Toggle::make('is_active')->default(true),
                Toggle::make('preorder_available')->default(false),
                SpatieMediaLibraryFileUpload::make('cover_image')
                    ->collection('cover_image')
                    ->image()
                    ->required(),
                SpatieMediaLibraryFileUpload::make('gallery')
                    ->collection('gallery')
                    ->multiple()
                    ->image()
                    ->enableReordering(),
            ]);
    }
}
