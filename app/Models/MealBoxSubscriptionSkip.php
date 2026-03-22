<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealBoxSubscriptionSkip extends Model
{
    //
     protected $fillable = [
        'meal_box_subscription_id',
        'skip_date',
    ];

    protected $casts = [
        'skip_date' => 'date',
    ];

    public function subscription()
    {
        return $this->belongsTo(MealBoxSubscription::class, 'meal_box_subscription_id');
    }
}
