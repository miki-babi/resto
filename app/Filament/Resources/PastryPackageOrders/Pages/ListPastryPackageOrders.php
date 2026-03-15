<?php

namespace App\Filament\Resources\PastryPackageOrders\Pages;

use App\Filament\Resources\PastryPackageOrders\PastryPackageOrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPastryPackageOrders extends ListRecords
{
    protected static string $resource = PastryPackageOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
