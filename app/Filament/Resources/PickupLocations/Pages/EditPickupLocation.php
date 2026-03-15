<?php

namespace App\Filament\Resources\PickupLocations\Pages;

use App\Filament\Resources\PickupLocations\PickupLocationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPickupLocation extends EditRecord
{
    protected static string $resource = PickupLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
