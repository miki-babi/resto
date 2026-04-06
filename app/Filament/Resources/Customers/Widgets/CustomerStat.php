<?php

namespace App\Filament\Resources\Customers\Widgets;

use App\Models\Customer;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CustomerStat extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalCustomers = Customer::count();
        $newCustomersInLastWeek = Customer::where('created_at', '>=', now()->subDays(7))->count();
        $taggedCustomers = Customer::query()
            ->where(function ($query): void {
                $query
                    ->whereJsonLength('tags->visit_behavior', '>', 0)
                    ->orWhereJsonLength('tags->order_behavior', '>', 0);
            })
            ->count();
        $customersWithPhone = Customer::query()
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->count();
        $taggedCoverage = $totalCustomers > 0
            ? (int) round(($taggedCustomers / $totalCustomers) * 100)
            : 0;
        $phoneCoverage = $totalCustomers > 0
            ? (int) round(($customersWithPhone / $totalCustomers) * 100)
            : 0;

        $today = now();
        $customerTrend = collect(range(6, 0))
            ->map(function (int $daysAgo) use ($today): int {
                return Customer::whereDate('created_at', $today->copy()->subDays($daysAgo))->count();
            })
            ->all();

        return [
            Stat::make('Total Customers', number_format($totalCustomers))
                ->icon('heroicon-m-users')
                ->description(number_format($newCustomersInLastWeek).' new in last 7 days')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($customerTrend)
                ->color('success'),
            Stat::make('Tagged Customers', number_format($taggedCustomers))
                ->icon('heroicon-m-tag')
                ->description($taggedCoverage.'% of total customers')
                ->color('info'),
            Stat::make('Phone Numbers Collected', number_format($customersWithPhone))
                ->icon('heroicon-m-device-phone-mobile')
                ->description($phoneCoverage.'% of total customers')
                ->color('primary'),
        ];
    }
}
