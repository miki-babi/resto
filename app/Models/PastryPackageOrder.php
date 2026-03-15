<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PastryPackageOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'pastry_customer_id',
        'pastry_package_id',
        'quantity',
        'total_price',

        'order_type',

        'pickup_location_id',
        'pickup_day_of_week',
        'pickup_hour_slot',
        'pickup_period',

        'delivery_phone',
        'delivery_address',

        'status',
        'payment_status',
    ];

    public function customer()
    {
        return $this->belongsTo(PastryCustomer::class, 'pastry_customer_id');
    }

    public function package()
    {
        return $this->belongsTo(PastryPackage::class, 'pastry_package_id');
    }

    public function pickupLocation()
    {
        return $this->belongsTo(PickupLocation::class);
    }
}