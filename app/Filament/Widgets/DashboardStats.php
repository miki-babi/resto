<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Feedback;
use App\Models\MealBoxSubscription;
use App\Models\PastryItemOrder;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1; // Stats first

    protected function getStats(): array
    {
        $customerCount = Customer::count();
        $newCustomers = Customer::where('created_at', '>=', now()->subDays(7))->count();
        $today = now();
        $customerChart = collect(range(6, 0))
            ->map(function (int $daysAgo) use ($today): int {
                return Customer::whereDate('created_at', $today->copy()->subDays($daysAgo))->count();
            })
            ->all();

        return [
            //
            Stat::make('Customers', number_format($customerCount))
                ->icon('heroicon-m-users')
                ->description(number_format($newCustomers).' new in last 7 days')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($customerChart)
                ->color('success'),
            Stat::make('Mealbox Subscriptions', MealBoxSubscription::where('status', 'active')->count())
                ->icon('heroicon-m-inbox-stack')
                ->chart([3, 5, 2, 8, 4, 6, 7])
                ->color('primary'),
            // ->color('success'),

            Stat::make('New Feedback', Feedback::where('complaint_status', 'pending')->count())
                ->icon('heroicon-m-chat-bubble-oval-left')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->description('Unresolved feedbacks')
                ->color('warning'),

            Stat::make('Pastry Pre-orders', PastryItemOrder::count())
                ->icon('heroicon-m-cake')
                ->chart([5, 10, 8, 15, 20])
                ->color('info'),
            // Stat::make('Unique views', '192.1k'),
            // Stat::make('Bounce rate', '21%'),
            // Stat::make('Average time on page', '3:12'),
        ];
    }
}
