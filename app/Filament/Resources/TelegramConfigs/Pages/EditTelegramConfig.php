<?php

namespace App\Filament\Resources\TelegramConfigs\Pages;

use App\Filament\Resources\TelegramConfigs\TelegramConfigResource;
use App\Services\TelegramBotService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditTelegramConfig extends EditRecord
{
    protected static string $resource = TelegramConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('set_webhook')
                ->label('Set Webhook')
                ->icon('heroicon-m-link')
                ->color('info')
                ->requiresConfirmation()
                ->action(function (TelegramBotService $telegramBotService): void {
                    $botToken = trim((string) $this->record->bot_token);

                    if ($botToken === '') {
                        throw ValidationException::withMessages([
                            'bot_token' => 'Bot token is required before setting webhook.',
                        ]);
                    }

                    $response = $telegramBotService->setWebhook($this->record);

                    if (! (bool) ($response['ok'] ?? false)) {
                        throw ValidationException::withMessages([
                            'bot_token' => (string) ($response['description'] ?? 'Telegram rejected webhook setup request.'),
                        ]);
                    }
                })
                ->successNotificationTitle('Webhook has been set successfully.'),
            DeleteAction::make(),
        ];
    }
}
