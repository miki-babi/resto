<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Loyality;
use App\Models\LoyalityRedemption;
use App\Models\MenuItem;
use App\Models\PastryItem;
use App\Models\PreOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LoyaltyService
{
    public function syncPreOrderLoyaltyState(PreOrder $preOrder): void
    {
        if (! $preOrder->exists || ! $preOrder->customer_id) {
            return;
        }

        DB::transaction(function () use ($preOrder): void {
            $lockedPreOrder = PreOrder::query()
                ->whereKey($preOrder->getKey())
                ->lockForUpdate()
                ->first();

            if (! $lockedPreOrder || ! $lockedPreOrder->customer_id) {
                return;
            }

            $lockedCustomer = Customer::query()
                ->whereKey($lockedPreOrder->customer_id)
                ->lockForUpdate()
                ->first();

            if (! $lockedCustomer) {
                return;
            }

            $isCompleted = $lockedPreOrder->status === 'completed';
            $isApplied = (bool) $lockedPreOrder->loyalty_points_applied;

            if ($isCompleted && ! $isApplied) {
                $earnedPoints = $this->calculatePreOrderPoints($lockedPreOrder);

                $lockedCustomer->forceFill([
                    'loyalty_points_balance' => (int) $lockedCustomer->loyalty_points_balance + $earnedPoints,
                ])->saveQuietly();

                $lockedPreOrder->forceFill([
                    'loyalty_points_earned' => $earnedPoints,
                    'loyalty_points_applied' => true,
                ])->saveQuietly();

                return;
            }

            if (! $isCompleted && $isApplied) {
                $earnedPoints = max(0, (int) $lockedPreOrder->loyalty_points_earned);

                $lockedCustomer->forceFill([
                    'loyalty_points_balance' => (int) $lockedCustomer->loyalty_points_balance - $earnedPoints,
                ])->saveQuietly();

                $lockedPreOrder->forceFill([
                    'loyalty_points_earned' => 0,
                    'loyalty_points_applied' => false,
                ])->saveQuietly();
            }
        });
    }

    public function calculatePreOrderPoints(PreOrder $preOrder): int
    {
        $itemsSummary = $this->normalizeItemsSummary($preOrder->items_summary);

        if ($itemsSummary === []) {
            return 0;
        }

        $menuQuantities = [];
        $pastryQuantities = [];

        foreach ($itemsSummary as $line) {
            if (! is_array($line)) {
                continue;
            }

            $itemId = (int) ($line['item_id'] ?? 0);
            $quantity = max(0, (int) ($line['quantity'] ?? 0));
            $itemType = strtolower(trim((string) ($line['item_type'] ?? '')));

            if ($itemId <= 0 || $quantity <= 0) {
                continue;
            }

            if ($itemType === 'menu') {
                $menuQuantities[$itemId] = ($menuQuantities[$itemId] ?? 0) + $quantity;

                continue;
            }

            if (in_array($itemType, ['cake', 'pastry'], true)) {
                $pastryQuantities[$itemId] = ($pastryQuantities[$itemId] ?? 0) + $quantity;
            }
        }

        $totalPoints = 0;

        if ($menuQuantities !== []) {
            $menuPointsById = MenuItem::query()
                ->whereIn('id', array_keys($menuQuantities))
                ->pluck('loyalty_points', 'id')
                ->all();

            foreach ($menuQuantities as $itemId => $quantity) {
                $itemPoints = max(0, (int) ($menuPointsById[$itemId] ?? 0));
                $totalPoints += $itemPoints * $quantity;
            }
        }

        if ($pastryQuantities !== []) {
            $pastryPointsById = PastryItem::query()
                ->whereIn('id', array_keys($pastryQuantities))
                ->pluck('loyalty_points', 'id')
                ->all();

            foreach ($pastryQuantities as $itemId => $quantity) {
                $itemPoints = max(0, (int) ($pastryPointsById[$itemId] ?? 0));
                $totalPoints += $itemPoints * $quantity;
            }
        }

        return $totalPoints;
    }

    public function redeemReward(
        Customer $customer,
        Loyality $loyality,
        ?PreOrder $preOrder = null,
        ?string $notes = null
    ): LoyalityRedemption {
        return DB::transaction(function () use ($customer, $loyality, $preOrder, $notes): LoyalityRedemption {
            $lockedCustomer = Customer::query()
                ->whereKey($customer->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $lockedLoyality = Loyality::query()
                ->whereKey($loyality->getKey())
                ->lockForUpdate()
                ->first();

            if (! $lockedLoyality || ! $lockedLoyality->is_active) {
                throw ValidationException::withMessages([
                    'loyality_id' => 'Selected reward is not active.',
                ]);
            }

            $lockedPreOrder = null;

            if ($preOrder) {
                $lockedPreOrder = PreOrder::query()
                    ->whereKey($preOrder->getKey())
                    ->lockForUpdate()
                    ->first();

                if (! $lockedPreOrder) {
                    throw ValidationException::withMessages([
                        'loyality_id' => 'Selected preorder could not be found.',
                    ]);
                }

                if ((int) $lockedPreOrder->customer_id !== (int) $lockedCustomer->id) {
                    throw ValidationException::withMessages([
                        'loyality_id' => 'Selected preorder does not belong to this customer.',
                    ]);
                }
            }

            $pointsRequired = max(1, (int) $lockedLoyality->points_required);

            if ((int) $lockedCustomer->loyalty_points_balance < $pointsRequired) {
                throw ValidationException::withMessages([
                    'loyality_id' => "Customer needs {$pointsRequired} points to redeem this reward.",
                ]);
            }

            $lockedCustomer->forceFill([
                'loyalty_points_balance' => (int) $lockedCustomer->loyalty_points_balance - $pointsRequired,
            ])->saveQuietly();

            return LoyalityRedemption::query()->create([
                'customer_id' => $lockedCustomer->id,
                'pre_order_id' => $lockedPreOrder?->id,
                'loyality_id' => $lockedLoyality->id,
                'points_spent' => $pointsRequired,
                'redeemed_at' => now(),
                'notes' => filled($notes) ? trim($notes) : null,
            ]);
        });
    }

    private function normalizeItemsSummary(mixed $itemsSummary): array
    {
        if (is_string($itemsSummary)) {
            $decoded = json_decode($itemsSummary, true);
            $itemsSummary = is_array($decoded) ? $decoded : [];
        }

        return is_array($itemsSummary) ? $itemsSummary : [];
    }
}
