<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealBoxPlan extends Model
{
    //
    protected $fillable = [
        'name',
        'price',
        'duration_days',
        'meals_per_day',
        'is_active'
    ];

    public function items()
    {
        return $this->hasMany(MealBoxPlanItem::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(MealBoxSubscription::class);
    }
}
