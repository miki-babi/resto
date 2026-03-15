<?php

namespace App\Filament\Resources\FeedbackLinks\Pages;

use App\Filament\Resources\FeedbackLinks\FeedbackLinkResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFeedbackLink extends EditRecord
{
    protected static string $resource = FeedbackLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
