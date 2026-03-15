<?php

namespace App\Filament\Resources\CateringRequests\Pages;

use App\Filament\Resources\CateringRequests\CateringRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCateringRequests extends ListRecords
{
    protected static string $resource = CateringRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
