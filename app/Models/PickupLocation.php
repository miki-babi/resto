<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PickupLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'is_active',
    ];

    public function hours()
    {
        return $this->hasMany(PickupLocationHour::class);
    }

    public function packageOrders()
    {
        return $this->hasMany(PastryPackageOrder::class);
    }

    public function itemOrders()
    {
        return $this->hasMany(PastryItemOrder::class);
    }
}