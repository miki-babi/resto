<?php

namespace App\Filament\Resources\TelegramPromos\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TelegramPromoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('caption')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('cta_label')
                    ->default(null),
                TextInput::make('cta_link')
                    ->default(null),
                Select::make('status')
                    ->options(['pending' => 'Pending', 'sent' => 'Sent', 'failed' => 'Failed'])
                    ->default('pending')
                    ->required(),
                SpatieMediaLibraryFileUpload::make('telegram_promo_images')
                    ->collection('telegram_promo_images')
                    ->multiple()
                    ->image()
                   // ->image()
                    ->imageEditor() // Enables the editor
                    ->imageEditorAspectRatios([
                        '16:9',
                        '4:3',
                        '1:1',
                    ]),
            ]);
    }
}
