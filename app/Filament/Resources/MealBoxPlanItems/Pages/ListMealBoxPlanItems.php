<?php

namespace App\Filament\Resources\MealBoxPlanItems\Pages;

use App\Filament\Resources\MealBoxPlanItems\MealBoxPlanItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMealBoxPlanItems extends ListRecords
{
    protected static string $resource = MealBoxPlanItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
