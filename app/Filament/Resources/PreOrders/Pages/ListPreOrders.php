<?php

namespace App\Filament\Resources\PreOrders\Pages;

use App\Filament\Resources\PreOrders\PreOrderResource;
use Filament\Resources\Pages\ListRecords;

class ListPreOrders extends ListRecords
{
    protected static string $resource = PreOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
