<?php

namespace App\Filament\Resources\TelegramConfigs\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TelegramConfigForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('miniapp_url')
                    ->url()
                    ->required(),
                TextInput::make('bot_token')
                    ->required(),
                Textarea::make('start_message')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('help_message')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
