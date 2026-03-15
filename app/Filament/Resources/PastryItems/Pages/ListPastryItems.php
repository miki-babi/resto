<?php

namespace App\Filament\Resources\PastryItems\Pages;

use App\Filament\Resources\PastryItems\PastryItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPastryItems extends ListRecords
{
    protected static string $resource = PastryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
