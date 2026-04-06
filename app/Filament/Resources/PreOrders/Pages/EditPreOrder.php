<?php

namespace App\Filament\Resources\PreOrders\Pages;

use App\Filament\Resources\PreOrders\PreOrderResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPreOrder extends EditRecord
{
    protected static string $resource = PreOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
