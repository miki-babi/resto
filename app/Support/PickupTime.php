<?php

namespace App\Support;

use Carbon\Carbon;
use InvalidArgumentException;

class PickupTime
{
    /**
     * Maps Ethiopian hour slots (1–12) to a 24h clock time.
     *
     * - day:  1 => 07:00 ... 12 => 18:00
     * - night: 1 => 19:00 ... 12 => 06:00 (next day)
     *
     * @return array{hour:int, add_day:bool}
     */
    public static function mapTo24Hour(string $period, int $hourSlot): array
    {
        if ($hourSlot < 1 || $hourSlot > 12) {
            throw new InvalidArgumentException('hour_slot must be between 1 and 12.');
        }

        if ($period === 'day') {
            return [
                'hour' => 6 + $hourSlot,
                'add_day' => false,
            ];
        }

        if ($period === 'night') {
            $hour = 18 + $hourSlot;

            return [
                'hour' => $hour % 24,
                'add_day' => $hour >= 24,
            ];
        }

        throw new InvalidArgumentException('pickup_period must be "day" or "night".');
    }

    public static function occurrenceForWeekAnchor(Carbon $anchor, int $dayOfWeek, int $hourSlot, string $period): Carbon
    {
        $base = $anchor->copy()
            ->startOfWeek(Carbon::SUNDAY)
            ->addDays($dayOfWeek);

        $mapping = self::mapTo24Hour(period: $period, hourSlot: $hourSlot);

        $dt = $base->copy()->setTime($mapping['hour'], 0);

        if ($mapping['add_day']) {
            $dt->addDay();
        }

        return $dt;
    }

    public static function nextOccurrence(Carbon $from, int $dayOfWeek, int $hourSlot, string $period): Carbon
    {
        $dt = self::occurrenceForWeekAnchor(
            anchor: $from,
            dayOfWeek: $dayOfWeek,
            hourSlot: $hourSlot,
            period: $period,
        );

        if ($dt->lessThan($from)) {
            $dt->addDays(7);
        }

        return $dt;
    }

    public static function label(Carbon $dt): string
    {
        return $dt->format('D, M j • g:i A');
    }
}

