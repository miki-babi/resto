<?php

namespace App\Filament\Resources\CateringRequests\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
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

                ToggleButtons::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'contacted' => 'Contacted',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->icons([
                        'pending' => 'heroicon-m-clock',
                        'contacted' => 'heroicon-m-chat-bubble-left-right',
                        'confirmed' => 'heroicon-m-check-badge',
                        'cancelled' => 'heroicon-m-x-circle',
                    ])
                    ->colors([
                        'pending' => 'warning',      // Amber/Yellow
                        'contacted' => 'info',       // Blue
                        'confirmed' => 'success',    // Green
                        'cancelled' => 'danger',     // Red
                    ])
                    ->default('pending')
                    ->inline() // Keeps them side-by-side
                    ->required(),
            ]);
    }
}
