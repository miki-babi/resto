<?php

namespace App\Models;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'feedback_link_id',
        'customer_name',
        'customer_phone',
        'stars',
        'complaint',
        'complaint_status',
        'review_requested',
        'review_requested_at'
    ];
   protected static function booted()
    {
        static::created(function ($feedback) {

            $phone = trim((string) ($feedback->customer_phone ?? ''));
            if ($phone === '') {
                return;
            }

            $name = trim((string) ($feedback->customer_name ?? ''));
            if ($name === '') {
                return;
            }

            try {
                Customer::firstOrCreate(
                    ['phone' => $phone],
                    ['name' => $name]
                );
            } catch (QueryException $e) {
                $message = strtolower($e->getMessage());
                $isPhoneUniqueViolation = $e->getCode() === '23000'
                    && str_contains($message, 'customers')
                    && str_contains($message, 'phone')
                    && (str_contains($message, 'unique') || str_contains($message, 'duplicate'));

                if ($isPhoneUniqueViolation) {
                    Customer::where('phone', $phone)->first();
                    return;
                }

                throw $e;
            }

        });
    }

    public function feedbackLink()
    {
        return $this->belongsTo(FeedbackLink::class);
    }

    
}
