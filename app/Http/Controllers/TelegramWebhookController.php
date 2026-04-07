<?php

namespace App\Http\Controllers;

use App\Filament\Resources\Pages\PageResource;
use App\Models\TelegramConfig;
use App\Services\TelegramBotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class TelegramWebhookController extends Controller
{
    public function __construct(private TelegramBotService $telegramBotService) {}

    public function handle(Request $request, TelegramConfig $telegramConfig): JsonResponse
    {
        try {
            $message = $request->input('message');

            if (! is_array($message)) {
                return response()->json(['ok' => true]);
            }

            $chatId = data_get($message, 'chat.id');

            if ($chatId === null) {
                return response()->json(['ok' => true]);
            }

            $messageText = trim((string) data_get($message, 'text', ''));

            if ($messageText === '/start') {
                $startPayload = $this->telegramBotService->startMessagePayload($chatId, $telegramConfig);

                $this->telegramBotService->sendMessage(
                    telegramConfig: $telegramConfig,
                    chatId: $chatId,
                    text: (string) ($startPayload['text'] ?? ''),
                    replyMarkup: $startPayload['reply_markup'] ?? null,
                );

                return response()->json(['ok' => true]);
            }

            if ($messageText === '/help') {
                $helpMessage = trim((string) ($telegramConfig->help_message ?? ''));

                $this->telegramBotService->sendMessage(
                    telegramConfig: $telegramConfig,
                    chatId: $chatId,
                    text: $helpMessage !== '' ? $helpMessage : 'Please choose one of the keyboard buttons.',
                    replyMarkup: $this->telegramBotService->mainReplyKeyboard($telegramConfig),
                );

                return response()->json(['ok' => true]);
            }

            $feature = $this->telegramBotService->resolveFeatureFromMessage($messageText);

            if ($feature === null) {
                return response()->json(['ok' => true]);
            }

            $this->telegramBotService->sendMessage(
                telegramConfig: $telegramConfig,
                chatId: $chatId,
                text: $this->featureResponseText($feature),
                replyMarkup: $this->telegramBotService->mainReplyKeyboard($telegramConfig),
            );
        } catch (Throwable $throwable) {
            Log::error('Telegram webhook processing failed.', [
                'telegram_config_id' => $telegramConfig->getKey(),
                'exception' => $throwable::class,
                'message' => $throwable->getMessage(),
            ]);
        }

        return response()->json(['ok' => true]);
    }

    private function featureResponseText(string $feature): string
    {
        return match ($feature) {
            TelegramBotService::FEATURE_ORDER_ONLINE => 'Order online here: '.route('preorder.menu'),
            TelegramBotService::FEATURE_CAKE_AND_PASTRY_PREORDER => 'Cake and pastry preorder here: '.route('preorder.cake'),
            TelegramBotService::FEATURE_CATERING_REQUEST => 'Please use our catering request page: '.route('catering.request.page'),
            TelegramBotService::FEATURE_MEALBOX_SUBSCRIPTION => 'Mealbox subscription page: '.route('mealbox.subscription'),
            TelegramBotService::FEATURE_FEEDBACK => 'Share feedback here: '.route('feedback.page'),
            TelegramBotService::FEATURE_MENU => 'Browse our menu here: '.route(PageResource::menuRouteName()),
            default => 'Please choose one of the keyboard buttons.',
        };
    }
}
