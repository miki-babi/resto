<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PastryCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'notes',
    ];

    protected static function booted()
    {
        static::created(function ($pastryCustomer) {

         Customer::firstOrCreate(
    ['phone' => $pastryCustomer->phone],
    [
        'name' => $pastryCustomer->name,
        'email' => $pastryCustomer->email
    ]
    );

        });
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