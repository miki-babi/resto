<?php

namespace App\Filament\Resources\PastryPackages\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class PastryPackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
            Textarea::make('description'),
            Toggle::make('is_active')->default(true),
            Toggle::make('is_customizable')->default(false),
            Toggle::make('show_item_price')->default(false),
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
