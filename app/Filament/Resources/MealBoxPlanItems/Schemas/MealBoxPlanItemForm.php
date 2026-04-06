<?php

namespace App\Filament\Resources\MealBoxPlanItems\Schemas;

use App\Models\MealBox;
use App\Models\MealBoxPlan;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MealBoxPlanItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextInput::make('meal_box_plan_id')
                //     ->required()
                //     ->numeric(),
                // TextInput::make('meal_box_id')
                //     ->required()
                //     ->numeric(),
                // TextInput::make('day_of_week')
                //     ->required()
                //     ->numeric(),

                Select::make('meal_box_plan_id')
                    ->label('Plan')
                    ->searchable()
                    ->options(MealBoxPlan::all()->pluck('name', 'id'))
                    ->required(),
                Select::make('meal_box_id')
                    ->label('Meal Box')
                    ->searchable()

                    ->options(MealBox::all()->pluck('name', 'id'))
                    ->required(),
                Select::make('day_of_week')
                    ->options([
                        1 => 'Monday',
                        2 => 'Tuesday',
                        3 => 'Wednesday',
                        4 => 'Thursday',
                        5 => 'Friday',
                        6 => 'Saturday',
                        7 => 'Sunday',
                    ])
                    ->required(),
            ]);
    }
}
