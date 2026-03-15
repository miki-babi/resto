<?php

namespace App\Filament\Resources\PastryPackages\Pages;

use App\Filament\Resources\PastryPackages\PastryPackageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPastryPackages extends ListRecords
{
    protected static string $resource = PastryPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
