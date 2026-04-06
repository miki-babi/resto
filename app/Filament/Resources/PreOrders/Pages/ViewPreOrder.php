<?php

namespace App\Filament\Resources\PreOrders\Pages;

use App\Filament\Resources\PreOrders\PreOrderResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPreOrder extends ViewRecord
{
    protected static string $resource = PreOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
