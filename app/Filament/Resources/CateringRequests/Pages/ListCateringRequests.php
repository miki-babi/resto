<?php

namespace App\Filament\Resources\CateringRequests\Pages;

use App\Filament\Resources\CateringRequests\CateringRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
// use Filament\Resources\Components\Tab;
// use Filament\Schemas\Components\Tabs\Tab as TabsTab;
use Illuminate\Database\Eloquent\Builder;

class ListCateringRequests extends ListRecords
{
    protected static string $resource = CateringRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->icon('heroicon-m-list-bullet'), // Added icon

            'pending' => Tab::make('Pending')
                ->icon('heroicon-m-clock') // Added icon
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending'))
                ->badge(fn () => $this->getModel()::where('status', 'pending')->count())
                ->badgeColor('warning'),

            'contacted' => Tab::make('Contacted')
                ->icon('heroicon-m-phone') // Added icon
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'contacted'))
                ->badge(fn () => $this->getModel()::where('status', 'contacted')->count())
                ->badgeColor('warning'),

            'confirmed' => Tab::make('Confirmed')
                ->icon('heroicon-m-check-circle') // Added icon
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'confirmed'))
                ->badge(fn () => $this->getModel()::where('status', 'confirmed')->count())
                ->badgeColor('success'), // Tip: changed to success for confirmed!

            'cancelled' => Tab::make('Cancelled')
                ->icon('heroicon-m-x-circle') // Added icon

                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'cancelled'))
                ->badge(fn () => $this->getModel()::where('status', 'cancelled')->count())
                ->badgeColor('danger'), // Tip: changed to danger for cancelled!
        ];
    }
}

//    ToggleButtons::make('status')
//     ->options([
//         'pending' => 'Pending',
//         'contacted' => 'Contacted',
//         'confirmed' => 'Confirmed',
//         'cancelled' => 'Cancelled',
//     ])
//     ->icons([
//         'pending' => 'heroicon-m-clock',
//         'contacted' => 'heroicon-m-chat-bubble-left-right',
//         'confirmed' => 'heroicon-m-check-badge',
//         'cancelled' => 'heroicon-m-x-circle',
//     ])
//     ->colors([
//         'pending' => 'warning',      // Amber/Yellow
//         'contacted' => 'info',       // Blue
//         'confirmed' => 'success',    // Green
//         'cancelled' => 'danger',     // Red
//     ])
