<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'telegram_user_id',
        'telegram_username',
        'notes',
        'tags',
        'loyalty_points_balance',
        'is_blocked',
    ];

    protected $casts = [
        'is_blocked' => 'boolean',
        'tags' => 'array',
        'loyalty_points_balance' => 'integer',
    ];

    public function subscriptions()
    {
        return $this->hasMany(MealBoxSubscription::class);
    }

    public function menuItemOrders()
    {
        return $this->hasMany(MenuItemOrder::class);
    }

    public function preOrders()
    {
        return $this->hasMany(PreOrder::class);
    }

    public function loyalityRedemptions()
    {
        return $this->hasMany(LoyalityRedemption::class);
    }
}
