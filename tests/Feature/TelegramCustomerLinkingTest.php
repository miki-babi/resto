<?php

use App\Models\Customer;
use App\Models\TelegramConfig;
use App\Services\TelegramBotService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;

uses(RefreshDatabase::class);

it('asks for phone number on start when telegram user is not linked', function () {
    $telegramConfig = createTelegramConfig();

    $this->mock(TelegramBotService::class, function (MockInterface $mock): void {
        $mock->shouldReceive('phoneRequestMessagePayload')
            ->once()
            ->with(9001)
            ->andReturn([
                'chat_id' => 9001,
                'text' => 'Please share your phone number to continue.',
                'reply_markup' => [
                    'keyboard' => [[['text' => 'Share phone number', 'request_contact' => true]]],
                    'resize_keyboard' => true,
                    'is_persistent' => false,
                    'one_time_keyboard' => true,
                ],
            ]);

        $mock->shouldReceive('sendMessage')
            ->once()
            ->andReturn([]);
    });

    $response = $this->postJson(route('telegram.webhook', ['telegramConfig' => $telegramConfig->id]), [
        'message' => [
            'chat' => ['id' => 9001],
            'from' => ['id' => 778899, 'username' => 'new_user'],
            'text' => '/start',
        ],
    ]);

    $response->assertOk();
    expect(Customer::count())->toBe(0);
});

it('links telegram user id to an existing customer when provided phone exists', function () {
    $telegramConfig = createTelegramConfig();

    $customer = Customer::create([
        'name' => 'Existing',
        'phone' => '0911111111',
    ]);

    $this->mock(TelegramBotService::class, function (MockInterface $mock): void {
        $mock->shouldReceive('phoneRequestMessagePayload')
            ->once()
            ->with(9002)
            ->andReturn([
                'chat_id' => 9002,
                'text' => 'Please share your phone number to continue.',
                'reply_markup' => [
                    'keyboard' => [[['text' => 'Share phone number', 'request_contact' => true]]],
                    'resize_keyboard' => true,
                    'is_persistent' => false,
                    'one_time_keyboard' => true,
                ],
            ]);

        $mock->shouldReceive('startMessagePayload')
            ->once()
            ->with(9002, Mockery::type(TelegramConfig::class))
            ->andReturn([
                'chat_id' => 9002,
                'text' => 'Welcome back!',
                'reply_markup' => [
                    'keyboard' => [],
                    'resize_keyboard' => true,
                    'is_persistent' => true,
                    'one_time_keyboard' => false,
                ],
            ]);

        $mock->shouldReceive('sendMessage')
            ->twice()
            ->andReturn([]);
    });

    $this->postJson(route('telegram.webhook', ['telegramConfig' => $telegramConfig->id]), [
        'message' => [
            'chat' => ['id' => 9002],
            'from' => ['id' => 11223344, 'username' => 'existing_user'],
            'text' => '/start',
        ],
    ])->assertOk();

    $this->postJson(route('telegram.webhook', ['telegramConfig' => $telegramConfig->id]), [
        'message' => [
            'chat' => ['id' => 9002],
            'from' => ['id' => 11223344, 'username' => 'existing_user'],
            'text' => '0911111111',
        ],
    ])->assertOk();

    expect(Customer::count())->toBe(1);
    expect($customer->fresh()->telegram_user_id)->toBe('11223344');
    expect($customer->fresh()->telegram_username)->toBe('existing_user');
});

it('creates a customer with phone and telegram user id when phone does not exist', function () {
    $telegramConfig = createTelegramConfig();

    $this->mock(TelegramBotService::class, function (MockInterface $mock): void {
        $mock->shouldReceive('phoneRequestMessagePayload')
            ->once()
            ->with(9003)
            ->andReturn([
                'chat_id' => 9003,
                'text' => 'Please share your phone number to continue.',
                'reply_markup' => [
                    'keyboard' => [[['text' => 'Share phone number', 'request_contact' => true]]],
                    'resize_keyboard' => true,
                    'is_persistent' => false,
                    'one_time_keyboard' => true,
                ],
            ]);

        $mock->shouldReceive('startMessagePayload')
            ->once()
            ->with(9003, Mockery::type(TelegramConfig::class))
            ->andReturn([
                'chat_id' => 9003,
                'text' => 'Welcome!',
                'reply_markup' => [
                    'keyboard' => [],
                    'resize_keyboard' => true,
                    'is_persistent' => true,
                    'one_time_keyboard' => false,
                ],
            ]);

        $mock->shouldReceive('sendMessage')
            ->twice()
            ->andReturn([]);
    });

    $this->postJson(route('telegram.webhook', ['telegramConfig' => $telegramConfig->id]), [
        'message' => [
            'chat' => ['id' => 9003],
            'from' => ['id' => 55667788, 'username' => 'new_phone_user'],
            'text' => '/start',
        ],
    ])->assertOk();

    $this->postJson(route('telegram.webhook', ['telegramConfig' => $telegramConfig->id]), [
        'message' => [
            'chat' => ['id' => 9003],
            'from' => ['id' => 55667788, 'username' => 'new_phone_user'],
            'text' => '+251 911 22 33 44',
        ],
    ])->assertOk();

    expect(Customer::count())->toBe(1);

    $createdCustomer = Customer::query()->first();

    expect($createdCustomer)->not()->toBeNull();
    expect($createdCustomer?->phone)->toBe('+251911223344');
    expect($createdCustomer?->telegram_user_id)->toBe('55667788');
    expect($createdCustomer?->telegram_username)->toBe('new_phone_user');
});

it('shows regular start menu for users already linked by telegram id', function () {
    $telegramConfig = createTelegramConfig();

    $customer = Customer::create([
        'name' => 'Linked User',
        'phone' => '0922222222',
        'telegram_user_id' => '99887766',
        'telegram_username' => null,
    ]);

    $this->mock(TelegramBotService::class, function (MockInterface $mock): void {
        $mock->shouldReceive('startMessagePayload')
            ->once()
            ->with(9004, Mockery::type(TelegramConfig::class))
            ->andReturn([
                'chat_id' => 9004,
                'text' => 'Welcome linked user!',
                'reply_markup' => [
                    'keyboard' => [],
                    'resize_keyboard' => true,
                    'is_persistent' => true,
                    'one_time_keyboard' => false,
                ],
            ]);

        $mock->shouldReceive('sendMessage')
            ->once()
            ->andReturn([]);
    });

    $this->postJson(route('telegram.webhook', ['telegramConfig' => $telegramConfig->id]), [
        'message' => [
            'chat' => ['id' => 9004],
            'from' => ['id' => 99887766, 'username' => 'linked_username'],
            'text' => '/start',
        ],
    ])->assertOk();

    expect(Customer::count())->toBe(1);
    expect($customer->fresh()->telegram_username)->toBe('linked_username');
});

function createTelegramConfig(): TelegramConfig
{
    return TelegramConfig::create([
        'miniapp_url' => 'https://example.com',
        'bot_token' => 'test-bot-token',
        'start_message' => 'Choose an option from the keyboard.',
        'help_message' => 'Use /start to open menu options.',
    ]);
}
