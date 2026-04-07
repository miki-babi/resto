<?php

namespace App\Filament\Resources\TelegramPromos\Pages;

use App\Filament\Resources\TelegramPromos\TelegramPromoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTelegramPromos extends ListRecords
{
    protected static string $resource = TelegramPromoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
