<?php

namespace App\Filament\Resources\MealBoxPlans\Pages;

use App\Filament\Resources\MealBoxPlans\MealBoxPlanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMealBoxPlans extends ListRecords
{
    protected static string $resource = MealBoxPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
