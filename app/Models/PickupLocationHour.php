<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PickupLocationHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'pickup_location_id',
        'day_of_week',
        'hour_slot',
        'period',
        'is_active',
    ];

    public function location()
    {
        return $this->belongsTo(PickupLocation::class, 'pickup_location_id');
    }
}