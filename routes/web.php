<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StaffMenuItemOrderController;
use App\Models\Feedback;
use App\Models\FeedbackLink;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('pages.landing.main');
})->name('home');
Route::get('/menu', function () {
    return view('pages.landing.menu');
})->name('menu');

Route::get('/login', function () {
    return redirect('/owner/login');
})->name('login');

Route::get('/order', [OrderController::class, 'index'])->name('order.index');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/{publicToken}', [OrderController::class, 'show'])->name('order.show');
Route::get('/order/{publicToken}/poll', [OrderController::class, 'poll'])->name('order.poll');

Route::middleware('auth')->prefix('staff')->group(function () {
    Route::get('/', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/pickup-locations/{pickupLocation}/command', [StaffController::class, 'command'])->name('staff.command');
    Route::get('/pickup-locations/{pickupLocation}/command/poll', [StaffController::class, 'poll'])->name('staff.command.poll');

    Route::post('/menu-item-orders/{order}/accept', [StaffMenuItemOrderController::class, 'accept'])->name('staff.orders.accept');
    Route::post('/menu-item-orders/{order}/ready', [StaffMenuItemOrderController::class, 'ready'])->name('staff.orders.ready');
    Route::post('/menu-item-orders/{order}/picked-up', [StaffMenuItemOrderController::class, 'pickedUp'])->name('staff.orders.picked_up');
});






Route::get('/feedback/{id}', function ($id) {
    try {
        $feedback = FeedbackLink::findOrFail($id);
        return view('pages.feedback', compact('feedback'));
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        Log::error("Feedback link not found: ID {$id}");
        return redirect('/')->withErrors('Feedback link not found.');
    } catch (\Exception $e) {
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
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        Log::error("Feedback link not found: ID {$id}");
        return redirect()->back()->withErrors('Feedback link not found.');
    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        Log::error("Error submitting feedback: {$e->getMessage()}");
        return redirect()->back()->withErrors('Something went wrong. Please try again.');
    }
})->name('feedback.submit');




Route::get('/{page}', function ($page) {
    return view('pages.show');
})->name('page');
Route::get('/{page}/{slug}', function ($page , $slug ) {
    return view('pages.show');
})->name('page.slug');
