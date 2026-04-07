<?php

use App\Filament\Resources\Pages\PageResource;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PreorderController;
use App\Http\Controllers\TelegramWebhookController;
use App\Models\Feedback;
use App\Models\FeedbackLink;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

Route::get('/storage', function () {
    Artisan::call('storage:link');

    return redirect()->back();
})->name('storage');

Route::get(PageResource::homeRoutePath(), [LandingController::class, 'home'])->name(PageResource::homeRouteName());
Route::get('/menu', [LandingController::class, 'menu'])->name(PageResource::menuRouteName());
Route::get('/catering', [LandingController::class, 'catering'])->name('catering');
Route::get('/catering/request', [LandingController::class, 'cateringRequest'])->name('catering.request.page');
Route::post('/catering/request', [LandingController::class, 'submitCateringRequest'])->name('catering.request');

Route::get('/preorder-menu', [PreorderController::class, 'showMenuPage'])->name('preorder.menu');
Route::post('/preorder-menu', [PreorderController::class, 'submitMenu'])->name('preorder.menu.submit');
Route::get('/preorder-menu/confirmation/{preOrder}', [PreorderController::class, 'showMenuConfirmation'])
    ->name('preorder.menu.confirmation');

Route::get('/preorder-cake', [PreorderController::class, 'showCakePage'])->name('preorder.cake');
Route::post('/preorder-cake', [PreorderController::class, 'submitCake'])->name('preorder.cake.submit');

Route::post('/telegram/webhook/{telegramConfig}', [TelegramWebhookController::class, 'handle'])
    ->name('telegram.webhook')
    ->whereNumber('telegramConfig')
    ->withoutMiddleware([ValidateCsrfToken::class]);

Route::get('/catering2', function () {
    return view('catering');
})->name('catering2');

Route::group(['prefix' => '{lang}', 'where' => ['lang' => 'en|am']], function () {
    Route::get('/catering2', function () {
        return view('catering');
    })->name('catering2.localized');
});

Route::get('/feedback/{id}', function ($id) {
    try {
        $feedback = FeedbackLink::findOrFail($id);

        return view('pages.feedback', compact('feedback'));
    } catch (ModelNotFoundException $e) {
        Log::error("Feedback link not found: ID {$id}");

        return redirect('/')->withErrors('Feedback link not found.');
    } catch (Exception $e) {
        Log::error("Error accessing feedback link: {$e->getMessage()}");

        return redirect('/')->withErrors('Something went wrong. Please try again.');
    }
})->name('feedback.show');

Route::post('/feedback/{id}', function (Request $request, $id) {
    try {
        $feedbackLink = FeedbackLink::findOrFail($id);

        if (! $request->filled('customer_name') && $request->filled('name')) {
            $request->merge(['customer_name' => $request->input('name')]);
        }

        $data = $request->validate([
            'stars' => 'required|integer|min:1|max:5',
            'complaint' => 'nullable|string',
            'customer_phone' => 'nullable|string|max:20',
            'customer_name' => 'required_with:customer_phone|string|max:255',
        ]);

        if (array_key_exists('customer_phone', $data)) {
            $data['customer_phone'] = trim((string) $data['customer_phone']);
            if ($data['customer_phone'] === '') {
                $data['customer_phone'] = null;
            }
        }

        if (array_key_exists('customer_name', $data)) {
            $data['customer_name'] = trim((string) $data['customer_name']);
            if ($data['customer_name'] === '') {
                $data['customer_name'] = null;
            }
        }

        $data['feedback_link_id'] = $feedbackLink->id;

        $feedback = Feedback::create($data);

        Log::info("Feedback submitted: ID {$feedback->id}, Stars: {$feedback->stars}, Complaint: {$feedback->complaint}");

        return redirect()->back()->with('success', 'Thank you for your feedback!');
    } catch (ModelNotFoundException $e) {
        Log::error("Feedback link not found: ID {$id}");

        return redirect()->back()->withErrors('Feedback link not found.');
    } catch (ValidationException $e) {
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (Exception $e) {
        Log::error("Error submitting feedback: {$e->getMessage()}");

        return redirect()->back()->withErrors('Something went wrong. Please try again.');
    }
})->name('feedback.submit');

Route::get(PageResource::pageRoutePath(), [LandingController::class, 'page'])
    ->name(PageResource::pageRouteName())
    ->where('slug', PageResource::pageRoutePattern());
