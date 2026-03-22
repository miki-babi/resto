<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealBoxPlanItem extends Model
{
    //
    protected $fillable = [
        'meal_box_plan_id',
        'meal_box_id',
        'day_of_week'
    ];

    public function plan()
    {
        return $this->belongsTo(MealBoxPlan::class, 'meal_box_plan_id');
    }

    public function mealBox()
    {
        return $this->belongsTo(MealBox::class);
    }
}
