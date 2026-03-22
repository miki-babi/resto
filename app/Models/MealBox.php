<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealBox extends Model
{
    //
    protected $fillable = [
        'name',
        'description',
        'price',
        'is_active'
    ];

    public function planItems()
    {
        return $this->hasMany(MealBoxPlanItem::class);
    }
}
