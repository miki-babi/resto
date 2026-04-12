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

    private const DEFAULT_START_MESSAGE = 'Please choose an option from the keyboard below.';

    private const PHONE_REQUEST_MESSAGE = 'Please share your phone number to continue.';

    private const SHARE_PHONE_BUTTON_TEXT = 'Share phone number';

    /**
     * @var list<string>
     */
    private const MAIN_MENU_FEATURES = [
        self::FEATURE_ORDER_ONLINE,
        self::FEATURE_CAKE_AND_PASTRY_PREORDER,
        self::FEATURE_CATERING_REQUEST,
        self::FEATURE_MEALBOX_SUBSCRIPTION,
        self::FEATURE_FEEDBACK,
        self::FEATURE_MENU,
    ];

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
     *     keyboard: array<int, array<int, array<string, mixed>>>,
     *     resize_keyboard: bool,
     *     is_persistent: bool,
     *     one_time_keyboard: bool
     * }
     */
    public function mainReplyKeyboard(?TelegramConfig $telegramConfig = null): array
    {
        $keyboard = [];

        foreach (self::MAIN_MENU_FEATURES as $feature) {
            $keyboard[] = [$this->featureMiniAppButton($feature)];
        }

        return $this->replyKeyboard(
            keyboard: $keyboard,
            isPersistent: true,
            oneTimeKeyboard: false,
        );
    }

    /**
     * @return array{chat_id: int|string, text: string, reply_markup: array<string, mixed>}
     */
    public function startMessagePayload(int|string $chatId, ?TelegramConfig $telegramConfig = null): array
    {
        return $this->messagePayload(
            chatId: $chatId,
            text: $this->startMessage($telegramConfig),
            replyMarkup: $this->mainReplyKeyboard($telegramConfig),
        );
    }

    /**
     * @return array{chat_id: int|string, text: string, reply_markup: array<string, mixed>}
     */
    public function phoneRequestMessagePayload(int|string $chatId): array
    {
        return $this->messagePayload(
            chatId: $chatId,
            text: self::PHONE_REQUEST_MESSAGE,
            replyMarkup: $this->phoneRequestReplyKeyboard(),
        );
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
        return $this->telegramRequestData(
            telegramConfig: $telegramConfig,
            method: 'setWebhook',
            payload: [
                'url' => $this->webhookUrl($telegramConfig),
                'allowed_updates' => ['message'],
            ],
        );
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

        return $this->telegramRequestData(
            telegramConfig: $telegramConfig,
            method: 'sendMessage',
            payload: $payload,
        );
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
        return route($this->featureRouteName($feature));
    }

    private function featureRouteName(string $feature): string
    {
        return match ($feature) {
            self::FEATURE_ORDER_ONLINE => 'preorder.menu',
            self::FEATURE_CAKE_AND_PASTRY_PREORDER => 'preorder.cake',
            self::FEATURE_CATERING_REQUEST => 'catering',
            self::FEATURE_MEALBOX_SUBSCRIPTION => 'mealbox.subscription',
            self::FEATURE_FEEDBACK => 'feedback.page',
            self::FEATURE_MENU => PageResource::menuRouteName(),
            default => PageResource::homeRouteName(),
        };
    }

    /**
     * @return array{
     *     text: string,
     *     web_app: array{url: string}
     * }
     */
    private function featureMiniAppButton(string $feature): array
    {
        return [
            'text' => $this->featureLabel($feature),
            'web_app' => [
                'url' => $this->featureRouteUrl($feature),
            ],
        ];
    }

    private function featureLabel(string $feature): string
    {
        return (string) (self::FEATURE_LABELS[$feature] ?? '');
    }

    private function startMessage(?TelegramConfig $telegramConfig): string
    {
        $startMessage = trim((string) ($telegramConfig?->start_message ?? ''));

        return $startMessage !== '' ? $startMessage : self::DEFAULT_START_MESSAGE;
    }

    /**
     * @param  array<int, array<int, array<string, mixed>>>  $keyboard
     * @return array{
     *     keyboard: array<int, array<int, array<string, mixed>>>,
     *     resize_keyboard: bool,
     *     is_persistent: bool,
     *     one_time_keyboard: bool
     * }
     */
    private function replyKeyboard(array $keyboard, bool $isPersistent, bool $oneTimeKeyboard): array
    {
        return [
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'is_persistent' => $isPersistent,
            'one_time_keyboard' => $oneTimeKeyboard,
        ];
    }

    /**
     * @return array{
     *     keyboard: array<int, array<int, array<string, mixed>>>,
     *     resize_keyboard: bool,
     *     is_persistent: bool,
     *     one_time_keyboard: bool
     * }
     */
    private function phoneRequestReplyKeyboard(): array
    {
        return $this->replyKeyboard(
            keyboard: [
                [
                    [
                        'text' => self::SHARE_PHONE_BUTTON_TEXT,
                        'request_contact' => true,
                    ],
                ],
            ],
            isPersistent: false,
            oneTimeKeyboard: true,
        );
    }

    /**
     * @param  array<string, mixed>  $replyMarkup
     * @return array{chat_id: int|string, text: string, reply_markup: array<string, mixed>}
     */
    private function messagePayload(int|string $chatId, string $text, array $replyMarkup): array
    {
        return [
            'chat_id' => $chatId,
            'text' => $text,
            'reply_markup' => $replyMarkup,
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function telegramRequestData(TelegramConfig $telegramConfig, string $method, array $payload): array
    {
        $data = $this->telegramRequest($telegramConfig, $method, $payload)->json();

        return is_array($data) ? $data : [];
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
