<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;


class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextInput::make('title')
                //     ->required(),
                // TextInput::make('slug')
                //     ->required(),
                // Textarea::make('hero_headline')
                //     ->default(null)
                //     ->columnSpanFull(),
                // Textarea::make('hero_subtitle')
                //     ->default(null)
                //     ->columnSpanFull(),
                // TextInput::make('primary_cta_text')
                //     ->default(null),
                // TextInput::make('primary_cta_url')
                //     ->url()
                //     ->default(null),
                // TextInput::make('secondary_cta_text')
                //     ->default(null),
                // TextInput::make('secondary_cta_url')
                //     ->url()
                //     ->default(null),
                // TextInput::make('location_id')
                //     ->numeric()
                //     ->default(null),
                // TextInput::make('menu_category_id')
                //     ->numeric()
                //     ->default(null),
                // TextInput::make('gallery_id')
                //     ->numeric()
                //     ->default(null),
                 TextInput::make('title')
                ->required(),

            TextInput::make('slug')
                ->required()
                ->unique(ignoreRecord: true),

            Textarea::make('hero_headline'),

            Textarea::make('hero_subtitle'),

            TextInput::make('primary_cta_text'),

            TextInput::make('primary_cta_url'),

            TextInput::make('secondary_cta_text'),

            TextInput::make('secondary_cta_url'),

            Select::make('location_id')
                ->relationship('location', 'name'),

            Select::make('menu_category_id')
                ->relationship('menuCategory', 'name'),

            Select::make('gallery_id')
                ->relationship('gallery', 'title'),

                SpatieMediaLibraryFileUpload::make('hero_image')
                ->disk('public')

    ->collection('hero_image'),

SpatieMediaLibraryFileUpload::make('hero_video')
    ->collection('hero_video')
                ->disk('public')

    ->acceptedFileTypes(['video/mp4', 'video/webm'])
    ->maxSize(102400), // 100MB
            ]);
    }
}
