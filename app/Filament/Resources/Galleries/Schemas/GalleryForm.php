<?php

namespace App\Filament\Resources\Galleries\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Support\Str;

class GalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(
                        fn($state, callable $set) =>
                        $set('slug', Str::slug($state))
                    ),

                    TextInput::make('public_title')
    ->label('Public Title')
    ->placeholder('What users will see')
    ->helperText('Optional. If empty, internal title will be used'),

                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),

                Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->default(true),

                SpatieMediaLibraryFileUpload::make('images')
                    ->collection('images')
                    ->multiple()
                    ->reorderable()
                    ->image()
                    ->imageEditor()
                    ->downloadable()
                    ->openable()
                    ->columnSpanFull(),
            ]);
    }
}
