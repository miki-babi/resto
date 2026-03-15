<?php

namespace App\Filament\Resources\FeedbackSmsTemplates\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class FeedbackSmsTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
            Textarea::make('content')->required()->hint('Use {link} as placeholder for review link'),
            ]);
    }
}
