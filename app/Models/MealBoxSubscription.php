<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealBoxSubscription extends Model
{
    //
    protected $fillable = [
        'customer_id',
        'meal_box_plan_id',
        'start_date',
        'end_date',
        'status',
        'delivery_time',
        'address',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'delivery_time' => 'array',
    ];

    public function plan()
    {
        return $this->belongsTo(MealBoxPlan::class, 'meal_box_plan_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
