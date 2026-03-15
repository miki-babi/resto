<?php

namespace App\Filament\Resources\PastryCustomers\Pages;

use App\Filament\Resources\PastryCustomers\PastryCustomerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPastryCustomer extends EditRecord
{
    protected static string $resource = PastryCustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
