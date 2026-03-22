<?php

namespace App\Filament\Resources\MealBoxes\Pages;

use App\Filament\Resources\MealBoxes\MealBoxResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMealBoxes extends ListRecords
{
    protected static string $resource = MealBoxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
