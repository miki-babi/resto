<?php

namespace App\Filament\Resources\MealBoxPlans\Pages;

use App\Filament\Resources\MealBoxPlans\MealBoxPlanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMealBoxPlan extends EditRecord
{
    protected static string $resource = MealBoxPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
