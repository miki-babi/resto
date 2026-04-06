<?php

namespace App\Filament\Resources\Loyalities\Pages;

use App\Filament\Resources\Loyalities\LoyalityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLoyalities extends ListRecords
{
    protected static string $resource = LoyalityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
