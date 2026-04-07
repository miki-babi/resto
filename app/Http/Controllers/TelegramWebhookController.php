<?php

namespace App\Http\Controllers;

use App\Filament\Resources\Pages\PageResource;
use App\Models\Customer;
use App\Models\TelegramConfig;
use App\Services\TelegramBotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
            $telegramUserId = trim((string) data_get($message, 'from.id', ''));
            $telegramUsername = trim((string) data_get($message, 'from.username', ''));

            $awaitingPhoneKey = $this->awaitingPhoneCacheKey($telegramConfig, $chatId);
            $isAwaitingPhone = Cache::get($awaitingPhoneKey, false) === true;

            if ($messageText === '/start') {
                if ($telegramUserId !== '' && $this->isTelegramUserLinked($telegramUserId)) {
                    Cache::forget($awaitingPhoneKey);
                    $this->syncTelegramUsername($telegramUserId, $telegramUsername);

                    $startPayload = $this->telegramBotService->startMessagePayload($chatId, $telegramConfig);

                    $this->telegramBotService->sendMessage(
                        telegramConfig: $telegramConfig,
                        chatId: $chatId,
                        text: (string) ($startPayload['text'] ?? ''),
                        replyMarkup: $startPayload['reply_markup'] ?? null,
                    );

                    return response()->json(['ok' => true]);
                }

                Cache::put($awaitingPhoneKey, true, now()->addMinutes(30));

                $phoneRequestPayload = $this->telegramBotService->phoneRequestMessagePayload($chatId);

                $this->telegramBotService->sendMessage(
                    telegramConfig: $telegramConfig,
                    chatId: $chatId,
                    text: (string) ($phoneRequestPayload['text'] ?? ''),
                    replyMarkup: $phoneRequestPayload['reply_markup'] ?? null,
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

            if ($isAwaitingPhone) {
                $phone = $this->extractPhoneFromMessage($message);

                if ($phone === null) {
                    $phoneRequestPayload = $this->telegramBotService->phoneRequestMessagePayload($chatId);

                    $this->telegramBotService->sendMessage(
                        telegramConfig: $telegramConfig,
                        chatId: $chatId,
                        text: 'Please share a valid phone number to continue.',
                        replyMarkup: $phoneRequestPayload['reply_markup'] ?? null,
                    );

                    return response()->json(['ok' => true]);
                }

                $this->linkCustomerByPhone(
                    phone: $phone,
                    telegramUserId: $telegramUserId,
                    telegramUsername: $telegramUsername,
                );

                Cache::forget($awaitingPhoneKey);

                $startPayload = $this->telegramBotService->startMessagePayload($chatId, $telegramConfig);

                $this->telegramBotService->sendMessage(
                    telegramConfig: $telegramConfig,
                    chatId: $chatId,
                    text: 'Thanks! You can now choose an option below.',
                    replyMarkup: $startPayload['reply_markup'] ?? null,
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

    private function awaitingPhoneCacheKey(TelegramConfig $telegramConfig, int|string $chatId): string
    {
        return 'telegram.awaiting_phone.'.$telegramConfig->getKey().'.'.(string) $chatId;
    }

    private function isTelegramUserLinked(string $telegramUserId): bool
    {
        return Customer::query()
            ->where('telegram_user_id', $telegramUserId)
            ->exists();
    }

    private function syncTelegramUsername(string $telegramUserId, string $telegramUsername): void
    {
        if ($telegramUsername === '') {
            return;
        }

        Customer::query()
            ->where('telegram_user_id', $telegramUserId)
            ->update([
                'telegram_username' => $telegramUsername,
            ]);
    }

    private function extractPhoneFromMessage(array $message): ?string
    {
        $contactPhone = trim((string) data_get($message, 'contact.phone_number', ''));

        if ($contactPhone !== '') {
            return $this->normalizePhone($contactPhone);
        }

        return $this->normalizePhone(trim((string) data_get($message, 'text', '')));
    }

    private function normalizePhone(string $phone): ?string
    {
        $normalized = preg_replace('/[^\d+]/', '', $phone);

        if ($normalized === null) {
            return null;
        }

        $normalized = trim($normalized);

        if ($normalized === '' || ! preg_match('/^\+?\d{7,20}$/', $normalized)) {
            return null;
        }

        return $normalized;
    }

    private function linkCustomerByPhone(string $phone, string $telegramUserId, string $telegramUsername): Customer
    {
        $customer = Customer::query()->firstWhere('phone', $phone);

        if (! $customer) {
            $customer = new Customer([
                'name' => $phone,
                'phone' => $phone,
            ]);
        }

        if (trim((string) $customer->name) === '') {
            $customer->name = $phone;
        }

        if ($telegramUserId !== '') {
            $customer->telegram_user_id = $telegramUserId;
        }

        $customer->telegram_username = $telegramUsername !== '' ? $telegramUsername : $customer->telegram_username;
        $customer->save();

        return $customer;
    }
}
