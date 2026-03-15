<?php

namespace App\Filament\Resources\PastryItems\Pages;

use App\Filament\Resources\PastryItems\PastryItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPastryItem extends EditRecord
{
    protected static string $resource = PastryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
