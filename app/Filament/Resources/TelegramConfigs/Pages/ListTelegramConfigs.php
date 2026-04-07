<?php

namespace App\Filament\Resources\TelegramConfigs\Pages;

use App\Filament\Resources\TelegramConfigs\TelegramConfigResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTelegramConfigs extends ListRecords
{
    protected static string $resource = TelegramConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
