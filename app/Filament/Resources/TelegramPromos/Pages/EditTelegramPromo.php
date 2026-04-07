<?php

namespace App\Filament\Resources\TelegramPromos\Pages;

use App\Filament\Resources\TelegramPromos\TelegramPromoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTelegramPromo extends EditRecord
{
    protected static string $resource = TelegramPromoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
