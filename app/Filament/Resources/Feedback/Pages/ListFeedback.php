<?php

namespace App\Filament\Resources\Feedback\Pages;

use App\Filament\Resources\Feedback\FeedbackResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
// use Filament\Resources\Components\Tab;
// use Filament\Schemas\Components\Tabs\Tab as TabsTab;
use Illuminate\Database\Eloquent\Builder;

class ListFeedback extends ListRecords
{
    protected static string $resource = FeedbackResource::class;

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
                ->modifyQueryUsing(fn (Builder $query) => $query->where('complaint_status', 'pending'))
                ->badge(fn () => $this->getModel()::where('complaint_status', 'pending')->count())
                ->badgeColor('warning'),

            'resolved' => Tab::make('Resolved')
                ->icon('heroicon-m-phone') // Added icon
                ->modifyQueryUsing(fn (Builder $query) => $query->where('complaint_status', 'resolved'))
                ->badge(fn () => $this->getModel()::where('complaint_status', 'resolved')->count())
                ->badgeColor('warning'),

        ];
    }
}
