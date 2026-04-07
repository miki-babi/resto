<?php

namespace App\Services;

use App\Filament\Resources\Pages\PageResource;
use App\Models\TelegramConfig;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class TelegramBotService
{
    public const FEATURE_ORDER_ONLINE = 'order_online';

    public const FEATURE_CAKE_AND_PASTRY_PREORDER = 'cake_and_pastry_preorder';

    public const FEATURE_CATERING_REQUEST = 'catering_request';

    public const FEATURE_MEALBOX_SUBSCRIPTION = 'mealbox_subscription';

    public const FEATURE_FEEDBACK = 'feedback';

    public const FEATURE_MENU = 'menu';

    /**
     * @var array<string, string>
     */
    private const FEATURE_LABELS = [
        self::FEATURE_ORDER_ONLINE => 'order online',
        self::FEATURE_CAKE_AND_PASTRY_PREORDER => 'cakeand pastry -preorder',
        self::FEATURE_CATERING_REQUEST => 'catering request',
        self::FEATURE_MEALBOX_SUBSCRIPTION => 'mealbox subscription',
        self::FEATURE_FEEDBACK => 'feedback',
        self::FEATURE_MENU => 'menu',
    ];

    /**
     * @return array<string, string>
     */
    public function featureLabels(): array
    {
        return self::FEATURE_LABELS;
    }

    /**
     * @return array{
     *     inline_keyboard: array<int, array<int, array<string, string>>>
     * }
     */
    public function mainReplyKeyboard(?TelegramConfig $telegramConfig = null): array
    {
        return [
            'inline_keyboard' => [
                [$this->featureUrlButton(self::FEATURE_ORDER_ONLINE)],
                [$this->featureUrlButton(self::FEATURE_CAKE_AND_PASTRY_PREORDER)],
                [$this->featureUrlButton(self::FEATURE_CATERING_REQUEST)],
                [$this->featureUrlButton(self::FEATURE_MEALBOX_SUBSCRIPTION)],
                [$this->featureUrlButton(self::FEATURE_FEEDBACK)],
                [$this->featureUrlButton(self::FEATURE_MENU)],
            ],
        ];
    }

    /**
     * @return array{chat_id: int|string, text: string, reply_markup: array<string, mixed>}
     */
    public function startMessagePayload(int|string $chatId, ?TelegramConfig $telegramConfig = null): array
    {
        $startMessage = trim((string) ($telegramConfig?->start_message ?? ''));

        return [
            'chat_id' => $chatId,
            'text' => $startMessage !== '' ? $startMessage : 'Please choose an option from the keyboard below.',
            'reply_markup' => $this->mainReplyKeyboard($telegramConfig),
        ];
    }

    public function resolveFeatureFromMessage(?string $messageText): ?string
    {
        if ($messageText === null) {
            return null;
        }

        $normalizedMessage = $this->normalizeButtonText($messageText);

        foreach (self::FEATURE_LABELS as $feature => $label) {
            if ($normalizedMessage === $this->normalizeButtonText($label)) {
                return $feature;
            }
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    public function setWebhook(TelegramConfig $telegramConfig): array
    {
        $response = $this->telegramRequest(
            telegramConfig: $telegramConfig,
            method: 'setWebhook',
            payload: [
                'url' => $this->webhookUrl($telegramConfig),
                'allowed_updates' => ['message'],
            ],
        );

        $data = $response->json();

        return is_array($data) ? $data : [];
    }

    /**
     * @param  array<string, mixed>|null  $replyMarkup
     * @return array<string, mixed>
     */
    public function sendMessage(
        TelegramConfig $telegramConfig,
        int|string $chatId,
        string $text,
        ?array $replyMarkup = null
    ): array {
        $payload = [
            'chat_id' => $chatId,
            'text' => $text,
        ];

        if ($replyMarkup !== null) {
            $payload['reply_markup'] = $replyMarkup;
        }

        $response = $this->telegramRequest(
            telegramConfig: $telegramConfig,
            method: 'sendMessage',
            payload: $payload,
        );

        $data = $response->json();

        return is_array($data) ? $data : [];
    }

    public function webhookUrl(TelegramConfig $telegramConfig): string
    {
        return route('telegram.webhook', ['telegramConfig' => $telegramConfig->getKey()]);
    }

    private function normalizeButtonText(string $text): string
    {
        $normalized = Str::of($text)
            ->replace(['–', '—'], '-')
            ->lower()
            ->squish()
            ->value();

        $withNormalizedHyphenSpacing = preg_replace('/\s*-\s*/', '-', $normalized);

        return trim($withNormalizedHyphenSpacing ?? $normalized);
    }

    private function featureRouteUrl(string $feature): string
    {
        return match ($feature) {
            self::FEATURE_ORDER_ONLINE => route('preorder.menu'),
            self::FEATURE_CAKE_AND_PASTRY_PREORDER => route('preorder.cake'),
            self::FEATURE_CATERING_REQUEST => route('catering.request.page'),
            self::FEATURE_MEALBOX_SUBSCRIPTION => route('mealbox.subscription'),
            self::FEATURE_FEEDBACK => route('feedback.page'),
            self::FEATURE_MENU => route(PageResource::menuRouteName()),
            default => route(PageResource::homeRouteName()),
        };
    }

    /**
     * @return array{text: string, url: string}
     */
    private function featureUrlButton(string $feature): array
    {
        return [
            'text' => (string) (self::FEATURE_LABELS[$feature] ?? ''),
            'url' => $this->featureRouteUrl($feature),
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function telegramRequest(TelegramConfig $telegramConfig, string $method, array $payload): Response
    {
        $botToken = trim((string) $telegramConfig->bot_token);

        if ($botToken === '') {
            throw new RuntimeException('Telegram bot token is missing.');
        }

        return Http::asJson()
            ->acceptJson()
            ->connectTimeout(5)
            ->timeout(15)
            ->retry([200, 500, 1000])
            ->post("https://api.telegram.org/bot{$botToken}/{$method}", $payload)
            ->throw();
    }
}
