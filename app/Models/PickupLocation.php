<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function menuItemOrders()
    {
        return $this->hasMany(MenuItemOrder::class);
    }

    public function preOrders()
    {
        return $this->hasMany(PreOrder::class);
    }
}
