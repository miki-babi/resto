<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

   protected function phone(): Attribute
{
    return Attribute::make(
        set: function ($value) {
            // 1. Remove all non-numeric characters (handles '+', ' ', '-', etc.)
            $phone = preg_replace('/[^0-9]/', '', $value);

            // 2. If it starts with '251', it's already in the desired format
            if (str_starts_with($phone, '251')) {
                return $phone;
            }

            // 3. If it starts with '09', replace the '0' with '251'
            if (str_starts_with($phone, '09')) {
                return '251' . substr($phone, 1);
            }

            // 4. Fallback: return as-is if it doesn't match standard patterns
            return $phone;
        },
    );
}
}
