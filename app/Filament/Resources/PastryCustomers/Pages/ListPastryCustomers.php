<?php

namespace App\Filament\Resources\PastryCustomers\Pages;

use App\Filament\Resources\PastryCustomers\PastryCustomerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPastryCustomers extends ListRecords
{
    protected static string $resource = PastryCustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
