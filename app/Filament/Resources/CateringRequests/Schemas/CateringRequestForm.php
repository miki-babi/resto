<?php

namespace App\Filament\Resources\CateringRequests\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class CateringRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('catering_package_id')
                ->relationship('package', 'name')
                ->required(),

            TextInput::make('name')->required(),
            TextInput::make('contact')->required(),
            Textarea::make('note'),

            Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'contacted' => 'Contacted',
                    'confirmed' => 'Confirmed',
                    'cancelled' => 'Cancelled',
                ])
                ->default('pending'),
            ]);
    }
}
