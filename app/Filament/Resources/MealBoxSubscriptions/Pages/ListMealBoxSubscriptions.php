<?php

namespace App\Filament\Resources\MealBoxSubscriptions\Pages;

use App\Filament\Resources\MealBoxSubscriptions\MealBoxSubscriptionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListMealBoxSubscriptions extends ListRecords
{
    protected static string $resource = MealBoxSubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
    //     public function getTabs(): array
    // {
    //     return [
    //         'all' => Tab::make('All Deliveries')
    //             ->icon('heroicon-m-list-bullet'),

    //         'morning' => Tab::make('Morning (12:00 - 5:00)')
    //             // ->icon('her')
    //             ->modifyQueryUsing(function (Builder $query) {
    //                 // This checks if ANY of the morning hours are in the array
    //                 $query->where(function ($q) {
    //                     $q->whereJsonContains('delivery_time', '06:00')
    //                       ->orWhereJsonContains('delivery_time', '07:00')
    //                       ->orWhereJsonContains('delivery_time', '08:00')
    //                       ->orWhereJsonContains('delivery_time', '09:00')
    //                       ->orWhereJsonContains('delivery_time', '10:00')
    //                       ->orWhereJsonContains('delivery_time', '11:00');
    //                 });
    //             })
    //             ->badge(fn () => $this->getModel()::whereJsonContains('delivery_time', '06:00')->count()), // Example count

    //         'afternoon' => Tab::make('Afternoon (6:00 - 11:00)')
    //             // ->icon('heroicon-m-cloud-sun')
    //             ->modifyQueryUsing(function (Builder $query) {
    //                 $query->where(function ($q) {
    //                     $q->whereJsonContains('delivery_time', '12:00')
    //                       ->orWhereJsonContains('delivery_time', '13:00')
    //                       ->orWhereJsonContains('delivery_time', '14:00')
    //                       ->orWhereJsonContains('delivery_time', '15:00')
    //                       ->orWhereJsonContains('delivery_time', '16:00')
    //                       ->orWhereJsonContains('delivery_time', '17:00');
    //                 });
    //             }),

    //         'evening' => Tab::make('Evening (12:00 night)')
    //             // ->icon('heroicon-m-moon')
    //             ->modifyQueryUsing(fn (Builder $query) =>
    //                 $query->whereJsonContains('delivery_time', '18:00')
    //             ),
    //     ];
    // }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Deliveries')
                ->icon('heroicon-m-list-bullet')
                ->badge(fn () => $this->getModel()::count()),

            'morning' => Tab::make('Morning (12:00 - 5:00)')
                // ->icon('heroicon-m-sun')
                ->modifyQueryUsing(function (Builder $query) {
                    $query->where(function ($q) {
                        $q->whereJsonContains('delivery_time', '06:00')
                            ->orWhereJsonContains('delivery_time', '07:00')
                            ->orWhereJsonContains('delivery_time', '08:00')
                            ->orWhereJsonContains('delivery_time', '09:00')
                            ->orWhereJsonContains('delivery_time', '10:00')
                            ->orWhereJsonContains('delivery_time', '11:00');
                    });
                })
            // Badge logic must match the query above
            // ->badge(fn () => $this->getModel()::where(function ($q) {
            //     $q->whereJsonContains('delivery_time', '06:00')
            //       ->orWhereJsonContains('delivery_time', '07:00')
            //       ->orWhereJsonContains('delivery_time', '08:00')
            //       ->orWhereJsonContains('delivery_time', '09:00')
            //       ->orWhereJsonContains('delivery_time', '10:00')
            //       ->orWhereJsonContains('delivery_time', '11:00');
            // })->count())
            // ->badgeColor('warning'),
            ,
            'afternoon' => Tab::make('Afternoon (6:00 - 11:00)')
                // ->icon('heroicon-m-cloud-sun')
                ->modifyQueryUsing(function (Builder $query) {
                    $query->where(function ($q) {
                        $q->whereJsonContains('delivery_time', '12:00')
                            ->orWhereJsonContains('delivery_time', '13:00')
                            ->orWhereJsonContains('delivery_time', '14:00')
                            ->orWhereJsonContains('delivery_time', '15:00')
                            ->orWhereJsonContains('delivery_time', '16:00')
                            ->orWhereJsonContains('delivery_time', '17:00');
                    });
                })
            // ->badge(fn () => $this->getModel()::where(function ($q) {
            //     $q->whereJsonContains('delivery_time', '12:00')
            //       ->orWhereJsonContains('delivery_time', '13:00')
            //       ->orWhereJsonContains('delivery_time', '14:00')
            //       ->orWhereJsonContains('delivery_time', '15:00')
            //       ->orWhereJsonContains('delivery_time', '16:00')
            //       ->orWhereJsonContains('delivery_time', '17:00');
            // })->count())
            // ->badgeColor('info'),
            ,

            'evening' => Tab::make('Evening (12:00 night)')
                // ->icon('heroicon-m-moon')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereJsonContains('delivery_time', '18:00')
                )
            // ->badge(fn () => $this->getModel()::whereJsonContains('delivery_time', '18:00')->count())
            // ->badgeColor('gray'),
            ,
        ];
    }
}
