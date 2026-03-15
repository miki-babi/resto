<?php

namespace App\Filament\Resources\PickupLocations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PickupLocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('address')
                    ->default(null),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
