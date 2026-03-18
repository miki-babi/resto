<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Str;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('question')
                    ->label('Question')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                Textarea::make('answer')
                    ->label('Answer')
                    ->rows(5)
                    ->required(),

                TextInput::make('slug')
                    ->label('Slug (SEO URL)')
                    ->required()
                    ->helperText('Used in clean URLs and search indexing.')
                    ->unique(ignoreRecord: true),

                TextInput::make('meta_title')
                    ->label('Meta Title')
                    ->maxLength(70)
                    ->placeholder('Optional override for search results')
                    ->helperText('If left empty, the question will be used for SEO.'),

                Textarea::make('meta_description')
                    ->label('Meta Description')
                    ->rows(3)
                    ->maxLength(160)
                    ->placeholder('Optional summary for search engines')
                    ->helperText('If left empty, a short excerpt from the answer will be used.'),

                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),

                TextInput::make('sort_order')
                    ->label('Sort Order')
                    ->default(0)
                    ->numeric(),
            ]);
    }
}
