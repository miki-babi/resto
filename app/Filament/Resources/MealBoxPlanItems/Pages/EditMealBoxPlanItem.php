<?php

namespace App\Filament\Resources\MealBoxPlanItems\Pages;

use App\Filament\Resources\MealBoxPlanItems\MealBoxPlanItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMealBoxPlanItem extends EditRecord
{
    protected static string $resource = MealBoxPlanItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
