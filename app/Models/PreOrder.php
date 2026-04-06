<?php

namespace App\Models;

use App\Services\LoyaltyService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PreOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'source_type',
        'source_id',
        'customer_id',
        'phone',
        'pickup_location_id',
        'pickup_date',
        'pickup_time',
        'total_price',
        'loyalty_points_earned',
        'loyalty_points_applied',
        'status',
        'payment_status',
        'items_summary',
    ];

    protected $casts = [
        'pickup_date' => 'date',
        'items_summary' => 'array',
        'total_price' => 'decimal:2',
        'loyalty_points_earned' => 'integer',
        'loyalty_points_applied' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $preOrder): void {
            $phone = trim((string) $preOrder->phone);

            if ($phone === '') {
                return;
            }

            $preOrder->phone = $phone;

            $customer = Customer::firstOrCreate(
                ['phone' => $phone],
                ['name' => $phone]
            );

            if (trim((string) $customer->name) === '') {
                $customer->name = $phone;
                $customer->save();
            }

            $preOrder->customer_id = $customer->id;
        });

        static::created(function (self $preOrder): void {
            if (trim((string) $preOrder->getAttribute('order_number')) !== '') {
                return;
            }

            $preOrder->forceFill([
                'order_number' => self::formatOrderNumber((int) $preOrder->getKey()),
            ])->saveQuietly();
        });

        static::saved(function (self $preOrder): void {
            app(LoyaltyService::class)->syncPreOrderLoyaltyState($preOrder);
        });
    }

    public function pickupLocation(): BelongsTo
    {
        return $this->belongsTo(PickupLocation::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function loyalityRedemptions(): HasMany
    {
        return $this->hasMany(LoyalityRedemption::class);
    }

    public function getOrderNumberAttribute(?string $value): string
    {
        if (trim((string) $value) !== '') {
            return $value;
        }

        $id = (int) ($this->getKey() ?? 0);
        if ($id <= 0) {
            return 'PO-PENDING';
        }

        return self::formatOrderNumber($id);
    }

    private static function formatOrderNumber(int $id): string
    {
        return 'PO-'.str_pad((string) $id, 6, '0', STR_PAD_LEFT);
    }
}
