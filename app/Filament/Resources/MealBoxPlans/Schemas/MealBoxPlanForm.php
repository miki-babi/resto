<?php

namespace App\Filament\Resources\MealBoxPlans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MealBoxPlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('duration_days')
                    ->required()
                    ->numeric(),
                TextInput::make('meals_per_day')
                    ->required()
                    ->numeric()
                    ->default(1),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
