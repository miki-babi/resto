<?php

use App\Models\Customer;
use App\Models\Feedback;
use App\Models\FeedbackLink;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a customer when feedback includes a new phone number', function () {
    $feedbackLink = FeedbackLink::create([
        'name' => 'Test Link',
        'address' => 'Test Address',
        'google_review_link' => 'https://example.com',
    ]);

    $response = $this->from("/feedback/{$feedbackLink->id}")
        ->postJson(route('feedback.submit', $feedbackLink->id), [
            'stars' => 2,
            'complaint' => 'Not happy',
            'customer_name' => 'John Doe',
            'customer_phone' => ' 0990000000 ',
        ]);

    $response->assertStatus(302);

    expect(Feedback::count())->toBe(1);
    expect(Customer::count())->toBe(1);
    expect(Customer::first()->phone)->toBe('0990000000');
    expect(Customer::first()->name)->toBe('John Doe');
});

it('does not create duplicate customers for the same phone number', function () {
    $feedbackLink = FeedbackLink::create([
        'name' => 'Test Link',
        'address' => 'Test Address',
        'google_review_link' => 'https://example.com',
    ]);

    $payload = [
        'stars' => 1,
        'complaint' => 'Bad experience',
        'customer_name' => 'Jane Doe',
        'customer_phone' => '0988888888',
    ];

    $this->from("/feedback/{$feedbackLink->id}")
        ->postJson(route('feedback.submit', $feedbackLink->id), $payload)
        ->assertStatus(302);

    $this->from("/feedback/{$feedbackLink->id}")
        ->postJson(route('feedback.submit', $feedbackLink->id), $payload)
        ->assertStatus(302);

    expect(Feedback::count())->toBe(2);
    expect(Customer::count())->toBe(1);
});

it('rejects feedback when phone is present but name is missing', function () {
    $feedbackLink = FeedbackLink::create([
        'name' => 'Test Link',
        'address' => 'Test Address',
        'google_review_link' => 'https://example.com',
    ]);

    $response = $this->from("/feedback/{$feedbackLink->id}")
        ->postJson(route('feedback.submit', $feedbackLink->id), [
            'stars' => 2,
            'complaint' => 'Not happy',
            'customer_phone' => '0990000000',
        ]);

    $response->assertStatus(302);
    $response->assertSessionHasErrors(['customer_name']);

    expect(Feedback::count())->toBe(0);
    expect(Customer::count())->toBe(0);
});

