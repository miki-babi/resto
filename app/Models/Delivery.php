<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = [
        'order_number',
        'customer_id',
        'delivery_phone',
        'delivery_address',
        'delivery_date',
        'total_price',
        'status',
        'payment_status',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'total_price' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $delivery): void {
            if (trim((string) $delivery->getAttribute('order_number')) === '') {
                // Temporary placeholder, will be updated after creation if needed
                // but better to pre-calculate or use a safe unique string if possible.
                // Standard Laravel pattern for custom IDs often uses a combination.
                $delivery->order_number = 'DL-TEMP-'.uniqid();
            }
        });

        static::created(function (self $delivery): void {
            if (str_starts_with($delivery->order_number, 'DL-TEMP-')) {
                $delivery->forceFill([
                    'order_number' => self::formatOrderNumber((int) $delivery->getKey()),
                ])->saveQuietly();
            }
        });
    }

    private static function formatOrderNumber(int $id): string
    {
        return 'DL-'.str_pad((string) $id, 6, '0', STR_PAD_LEFT);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(DeliveryItem::class);
    }

    /**
     * Get distinct past addresses for a customer, newest first.
     */
    public static function getPastAddressesForCustomer(int $customerId): array
    {
        return self::where('customer_id', $customerId)
            ->whereNotNull('delivery_address')
            ->where('delivery_address', '!=', '')
            ->orderBy('created_at', 'desc')
            ->pluck('delivery_address')
            ->unique()
            ->values()
            ->all();
    }
}
