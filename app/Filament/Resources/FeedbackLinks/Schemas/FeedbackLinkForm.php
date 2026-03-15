<?php

namespace App\Filament\Resources\FeedbackLinks\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class FeedbackLinkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            TextInput::make('name')->required(),
            TextInput::make('address')->required(),
            TextInput::make('google_review_link')->url()->required(),
            ]);
    }
}
