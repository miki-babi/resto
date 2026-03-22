<?php

namespace App\Filament\Resources\MealBoxSubscriptions\Pages;

use App\Filament\Resources\MealBoxSubscriptions\MealBoxSubscriptionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMealBoxSubscription extends EditRecord
{
    protected static string $resource = MealBoxSubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
