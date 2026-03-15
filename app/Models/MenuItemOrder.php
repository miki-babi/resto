<?php

namespace App\Models;

use App\Support\PickupTime;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItemOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'public_token',
        'pickup_location_id',
        'pickup_day_of_week',
        'pickup_hour_slot',
        'pickup_period',
        'total_price',
        'status',
    ];

    public function pickupLocation()
    {
        return $this->belongsTo(PickupLocation::class);
    }

    public function items()
    {
        return $this->hasMany(MenuItemOrderItem::class);
    }

    public function pickupAt(): CarbonInterface
    {
        if (! $this->created_at) {
            return now();
        }

        $dayOfWeek = (int) $this->pickup_day_of_week;
        $hourSlot = (int) $this->pickup_hour_slot;
        $period = (string) $this->pickup_period;

        $pickupAt = PickupTime::occurrenceForWeekAnchor(
            anchor: $this->created_at->copy(),
            dayOfWeek: $dayOfWeek,
            hourSlot: $hourSlot,
            period: $period,
        );

        if ($pickupAt->lessThan($this->created_at)) {
            $pickupAt->addDays(7);
        }

        return $pickupAt;
    }

    public function minutesLeft(): int
    {
        return now()->diffInMinutes($this->pickupAt(), false);
    }

    public function pickupLabel(): string
    {
        return PickupTime::label($this->pickupAt());
    }
}
