<?php

namespace App\Filament\Resources\Locations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Schema;

class LocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextInput::make('name')
                //     ->required(),
                // Textarea::make('google_maps_embed_url')
                //     ->default(null)
                //     ->columnSpanFull(),
                // TextInput::make('address')
                //     ->default(null),
                // Textarea::make('contact_phone')
                //     ->default(null)
                //     ->columnSpanFull(),
                TextInput::make('name')
                ->required(),

            Textarea::make('google_maps_embed_url'),

            TextInput::make('address'),

            Repeater::make('contact_phone')
    ->schema([
        TextInput::make('value')->required(),
    ])
            ]);
    }
}
