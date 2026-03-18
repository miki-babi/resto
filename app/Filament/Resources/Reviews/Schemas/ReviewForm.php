<?php

namespace App\Filament\Resources\Reviews\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class ReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                TextInput::make('reviewer_name')
            ->label('Reviewer Name')
            ->required(),

        SpatieMediaLibraryFileUpload::make('avatar')
            ->collection('avatar')
            ->label('Reviewer Avatar')
            ->image()
            ->imageEditor(),
            // ->single(),

        Textarea::make('content')
            ->label('Review Content')
            ->required(),

        Select::make('stars')
            ->label('Stars')
            ->options([
                1 => '1 Star',
                2 => '2 Stars',
                3 => '3 Stars',
                4 => '4 Stars',
                5 => '5 Stars',
            ])
            ->required()
            ->default(5),

        Toggle::make('is_featured')
            ->label('Featured Review')
            ->default(false),

        TextInput::make('sort_order')
            ->label('Sort Order')
            ->default(0)
            ->numeric(),
            ]);
    }
}
