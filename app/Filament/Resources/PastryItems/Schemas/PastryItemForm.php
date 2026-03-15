<?php

namespace App\Filament\Resources\PastryItems\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;



class PastryItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
            Textarea::make('description'),
            TextInput::make('price')->numeric(),
            Toggle::make('is_active')->default(true),
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
