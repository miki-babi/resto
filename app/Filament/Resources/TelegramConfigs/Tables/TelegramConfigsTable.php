<?php

namespace App\Filament\Resources\TelegramConfigs\Tables;

use App\Models\TelegramConfig;
use App\Services\TelegramBotService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Validation\ValidationException;

class TelegramConfigsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('miniapp_url')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('set_webhook')
                    ->label('Set Webhook')
                    ->icon('heroicon-m-link')
                    ->color('info')
                    ->requiresConfirmation()
                    ->action(function (TelegramConfig $record, TelegramBotService $telegramBotService): void {
                        $botToken = trim((string) $record->bot_token);

                        if ($botToken === '') {
                            throw ValidationException::withMessages([
                                'bot_token' => 'Bot token is required before setting webhook.',
                            ]);
                        }

                        $response = $telegramBotService->setWebhook($record);

                        if (! (bool) ($response['ok'] ?? false)) {
                            throw ValidationException::withMessages([
                                'bot_token' => (string) ($response['description'] ?? 'Telegram rejected webhook setup request.'),
                            ]);
                        }
                    })
                    ->successNotificationTitle('Webhook has been set successfully.'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
