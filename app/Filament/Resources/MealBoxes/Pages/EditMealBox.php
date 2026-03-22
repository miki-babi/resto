<?php

namespace App\Filament\Resources\MealBoxes\Pages;

use App\Filament\Resources\MealBoxes\MealBoxResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMealBox extends EditRecord
{
    protected static string $resource = MealBoxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
