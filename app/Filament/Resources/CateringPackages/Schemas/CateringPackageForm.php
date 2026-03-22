<?php

namespace App\Filament\Resources\CateringPackages\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;


class CateringPackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
                Textarea::make('description'),
                TextInput::make('min_guests')->numeric()->required(),

                TextInput::make('price_per_person')
                    ->numeric()
                    ->prefix('ETB')
                    ->label('Price Per Person')
                    ->helperText('Optional. Leave empty to hide per-person pricing.'),

                TextInput::make('price_total')
                    ->numeric()
                    ->prefix('ETB')
                    ->label('Price Per Package')
                    ->helperText('Optional. Leave empty to hide full-package pricing.'),

                TextInput::make('badge_text')
                    ->label('Badge Text')
                    ->helperText('Optional tag shown above the package title.'),

                Select::make('badge_variant')
                    ->label('Badge Style')
                    ->options([
                        'emerald' => 'Emerald',
                        'accent' => 'Accent',
                        'gold' => 'Gold',
                        'neutral' => 'Neutral',
                    ])
                    ->native(false)
                    ->helperText('Optional. Controls the badge color.'),

                Repeater::make('highlights')
                    ->label('Highlights')
                    ->simple(
                        TextInput::make('value')
                            ->label('Highlight')
                            ->required()
                    )
                    ->reorderable()
                    ->helperText('Optional bullet list shown on the package card.'),

                Toggle::make('is_active')->default(true),

                SpatieMediaLibraryFileUpload::make('cover_image')
                    ->collection('cover_image')
                    ->disk('public')
                    ->image(),

                SpatieMediaLibraryFileUpload::make('gallery')
                    ->collection('gallery')
                    ->disk('public')
                    ->multiple()
                    ->image()
                    ->enableReordering(),
            ]);
    }
}
