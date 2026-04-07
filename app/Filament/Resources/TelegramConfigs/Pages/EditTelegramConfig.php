<?php

namespace App\Filament\Resources\TelegramConfigs\Pages;

use App\Filament\Resources\TelegramConfigs\TelegramConfigResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTelegramConfig extends EditRecord
{
    protected static string $resource = TelegramConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
