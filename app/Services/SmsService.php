<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class SmsService
{
    /**
     * Send an SMS using the SMS Ethiopia API.
     */
    public static function send(string $msisdn, string $text): Response
    {
        $apiKey = trim((string) config('services.sms_ethiopia.key'));
        $baseUrl = rtrim((string) config('services.sms_ethiopia.base_url'), '/');
        $recipient = trim($msisdn);
        $message = trim($text);

        if ($apiKey === '') {
            throw new RuntimeException('SMS Ethiopia API key is missing.');
        }

        if ($baseUrl === '') {
            throw new RuntimeException('SMS Ethiopia base URL is missing.');
        }

        if ($recipient === '') {
            throw new RuntimeException('SMS recipient is missing.');
        }

        if ($message === '') {
            throw new RuntimeException('SMS text is missing.');
        }

        return Http::asJson()
            ->acceptJson()
            ->withHeaders([
                'KEY' => $apiKey,
            ])
            ->connectTimeout(5)
            ->timeout(15)
            ->retry([200, 500, 1000])
            ->post("{$baseUrl}/sms/send", [
                'msisdn' => $recipient,
                'text' => $message,
            ])
            ->throw();
    }
}
