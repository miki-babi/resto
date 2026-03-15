<?php

namespace App\Filament\Resources\PastryItemOrders\Pages;

use App\Filament\Resources\PastryItemOrders\PastryItemOrderResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPastryItemOrder extends EditRecord
{
    protected static string $resource = PastryItemOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
