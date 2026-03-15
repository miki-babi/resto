<?php

namespace App\Filament\Resources\FeedbackLinks\Pages;

use App\Filament\Resources\FeedbackLinks\FeedbackLinkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFeedbackLinks extends ListRecords
{
    protected static string $resource = FeedbackLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
