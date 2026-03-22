<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'notes',
        'is_blocked',
    ];

    protected $casts = [
        'is_blocked' => 'boolean',
    ];
    public function subscriptions()
    {
        return $this->hasMany(MealBoxSubscription::class);
    }
}
