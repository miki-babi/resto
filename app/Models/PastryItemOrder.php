<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PastryItemOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'pastry_customer_id',

        'order_type',

        'pickup_location_id',
        'pickup_day_of_week',
        'pickup_hour_slot',
        'pickup_period',

        'delivery_phone',
        'delivery_address',

        'total_price',

        'status',
        'payment_status',
    ];

    public function customer()
    {
        return $this->belongsTo(PastryCustomer::class, 'pastry_customer_id');
    }

    public function items()
    {
        return $this->hasMany(PastryItemOrderItem::class);
    }

    public function pickupLocation()
    {
        return $this->belongsTo(PickupLocation::class);
    }
}