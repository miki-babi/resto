<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MenuItemOrder extends Model
{
    use HasFactory;

    protected static ?bool $hasPublicTokenColumn = null;

    protected $fillable = [
        'customer_id',
        'pickup_location_id',
        'pickup_date',
        'pickup_time',
        'pickup_day_of_week',
        'pickup_hour_slot',
        'pickup_period',
        'total_price',
        'status',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $order): void {
            if (self::hasPublicTokenColumn() && blank($order->getAttribute('public_token'))) {
                $order->setAttribute('public_token', (string) Str::ulid());
            }
        });
    }

    private static function hasPublicTokenColumn(): bool
    {
        if (self::$hasPublicTokenColumn === null) {
            self::$hasPublicTokenColumn = Schema::hasColumn('menu_item_orders', 'public_token');
        }

        return self::$hasPublicTokenColumn;
    }

    public function items()
    {
        return $this->hasMany(MenuItemOrderItem::class);
    }

    public function pickupLocation()
    {
        return $this->belongsTo(PickupLocation::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
