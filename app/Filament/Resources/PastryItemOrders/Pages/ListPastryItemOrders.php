<?php

namespace App\Filament\Resources\PastryItemOrders\Pages;

use App\Filament\Resources\PastryItemOrders\PastryItemOrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPastryItemOrders extends ListRecords
{
    protected static string $resource = PastryItemOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
