<?php

namespace App\Filament\Resources\SmsPromos\Pages;

use App\Filament\Resources\SmsPromos\SmsPromoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSmsPromos extends ListRecords
{
    protected static string $resource = SmsPromoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
