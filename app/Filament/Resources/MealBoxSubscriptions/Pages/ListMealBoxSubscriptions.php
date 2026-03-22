<?php

namespace App\Filament\Resources\MealBoxSubscriptions\Pages;

use App\Filament\Resources\MealBoxSubscriptions\MealBoxSubscriptionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMealBoxSubscriptions extends ListRecords
{
    protected static string $resource = MealBoxSubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
