<?php

namespace App\Filament\Resources\MealBoxSubscriptionSkips\Pages;

use App\Filament\Resources\MealBoxSubscriptionSkips\MealBoxSubscriptionSkipResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMealBoxSubscriptionSkip extends EditRecord
{
    protected static string $resource = MealBoxSubscriptionSkipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
