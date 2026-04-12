<?php

use App\Services\SmsService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

it('sends sms requests to sms ethiopia', function () {
    Http::preventStrayRequests();
    Http::fake([
        'https://smsethiopia.et/api/sms/send' => Http::response(['ok' => true], 200),
    ]);

    config()->set('services.sms_ethiopia.base_url', 'https://smsethiopia.et/api');
    config()->set('services.sms_ethiopia.key', 'test-api-key');

    $response = app(SmsService::class)->send('251911639555', 'Hello World');

    expect($response->successful())->toBeTrue();

    Http::assertSent(function (Request $request): bool {
        return $request->method() === 'POST'
            && $request->url() === 'https://smsethiopia.et/api/sms/send'
            && $request->hasHeader('KEY', 'test-api-key')
            && $request->hasHeader('Content-Type', 'application/json')
            && $request['msisdn'] === '251911639555'
            && $request['text'] === 'Hello World';
    });
});

it('requires an sms ethiopia api key', function () {
    config()->set('services.sms_ethiopia.base_url', 'https://smsethiopia.et/api');
    config()->set('services.sms_ethiopia.key', '');

    expect(fn () => app(SmsService::class)->send('251967072576', 'Hello World'))
        ->toThrow(\RuntimeException::class, 'SMS Ethiopia API key is missing.');
});
