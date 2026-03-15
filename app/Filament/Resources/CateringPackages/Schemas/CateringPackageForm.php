<?php

namespace App\Filament\Resources\CateringPackages\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;


class CateringPackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                 TextInput::make('name')->required(),
            Textarea::make('description'),
            TextInput::make('min_guests')->numeric()->required(),
            Toggle::make('is_active')->default(true),

            SpatieMediaLibraryFileUpload::make('cover_image')
                ->collection('cover_image')
                ->image(),

            SpatieMediaLibraryFileUpload::make('gallery')
                ->collection('gallery')
                ->multiple()
                ->image()
                ->enableReordering(),
            ]);
    }
}
