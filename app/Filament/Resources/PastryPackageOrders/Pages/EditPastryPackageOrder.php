<?php

namespace App\Filament\Resources\PastryPackageOrders\Pages;

use App\Filament\Resources\PastryPackageOrders\PastryPackageOrderResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPastryPackageOrder extends EditRecord
{
    protected static string $resource = PastryPackageOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
