<?php

namespace App\Filament\Resources\Loyalities\Schemas;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LoyalityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                SpatieMediaLibraryFileUpload::make('reward_image')
                    ->label('Reward Image')
                    ->collection('reward_image')
                    ->image()
                    ->imageEditor(),
                TextInput::make('points_required')
                    ->numeric()
                    ->required()
                    ->default(1)
                    ->minValue(1),
                Toggle::make('is_active')
                    ->default(true),
                TextInput::make('sort_order')
                    ->numeric()
                    ->required()
                    ->default(0),
            ]);
    }
}
