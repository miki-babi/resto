<?php

use Illuminate\Support\Facades\Route;
use App\Models\Feedback;
use App\Models\FeedbackLink;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Artisan;

Route::get('/storage', function () {
    Artisan::call('storage:link');
    return redirect()->back();
})->name('storage');

Route::get('/', [LandingController::class, 'home'])->name('home');
Route::get('/menu', [LandingController::class, 'menu'])->name('menu');






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
Route::get('/{page}/{slug}', function ($page, $slug) {
    return view('pages.show');
})->name('page.slug');
