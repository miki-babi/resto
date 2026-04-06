<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoyalityRedemption extends Model
{
    protected $fillable = [
        'customer_id',
        'pre_order_id',
        'loyality_id',
        'points_spent',
        'redeemed_at',
        'notes',
    ];

    protected $casts = [
        'points_spent' => 'integer',
        'redeemed_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function preOrder(): BelongsTo
    {
        return $this->belongsTo(PreOrder::class);
    }

    public function loyality(): BelongsTo
    {
        return $this->belongsTo(Loyality::class);
    }
}
