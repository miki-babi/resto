<?php

namespace App\Filament\Resources\CateringRequests\Pages;

use App\Filament\Resources\CateringRequests\CateringRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCateringRequest extends EditRecord
{
    protected static string $resource = CateringRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
