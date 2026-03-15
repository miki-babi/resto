<?php

namespace App\Filament\Resources\FeedbackSmsTemplates\Pages;

use App\Filament\Resources\FeedbackSmsTemplates\FeedbackSmsTemplateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFeedbackSmsTemplates extends ListRecords
{
    protected static string $resource = FeedbackSmsTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
