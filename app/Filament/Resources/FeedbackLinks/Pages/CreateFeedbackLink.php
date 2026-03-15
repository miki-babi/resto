<?php

namespace App\Filament\Resources\FeedbackLinks\Pages;

use App\Filament\Resources\FeedbackLinks\FeedbackLinkResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFeedbackLink extends CreateRecord
{
    protected static string $resource = FeedbackLinkResource::class;
}
