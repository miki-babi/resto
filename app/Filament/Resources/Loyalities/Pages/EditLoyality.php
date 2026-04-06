<?php

namespace App\Filament\Resources\Loyalities\Pages;

use App\Filament\Resources\Loyalities\LoyalityResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLoyality extends EditRecord
{
    protected static string $resource = LoyalityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
