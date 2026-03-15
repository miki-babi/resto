<?php

namespace App\Filament\Resources\FeedbackSmsTemplates\Pages;

use App\Filament\Resources\FeedbackSmsTemplates\FeedbackSmsTemplateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFeedbackSmsTemplate extends EditRecord
{
    protected static string $resource = FeedbackSmsTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
