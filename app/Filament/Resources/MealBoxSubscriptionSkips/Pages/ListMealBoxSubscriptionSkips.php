<?php

namespace App\Filament\Resources\MealBoxSubscriptionSkips\Pages;

use App\Filament\Resources\MealBoxSubscriptionSkips\MealBoxSubscriptionSkipResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMealBoxSubscriptionSkips extends ListRecords
{
    protected static string $resource = MealBoxSubscriptionSkipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
