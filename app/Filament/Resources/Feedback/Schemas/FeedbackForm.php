<?php

namespace App\Filament\Resources\Feedback\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;

class FeedbackForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                Select::make('feedback_link_id')
                    ->relationship('feedbackLink', 'name')
                    ->searchable()
                    ->required(),
                TextInput::make('customer_name'),
                TextInput::make('customer_phone'),
                // Select::make('stars')
                //     ->options([1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5'])
                //     ->required(),
                Textarea::make('complaint')->visible(fn ($get) => $get('stars') < 3),
                Select::make('complaint_status')->options([
                    'pending' => 'Pending',
                    'resolved' => 'Resolved',
                ])->default('pending'),
                Toggle::make('review_requested'),
                DateTimePicker::make('review_requested_at'),
                ToggleButtons::make('stars')
                    ->label('Rating')
                    ->options([
                        1 => '1',
                        2 => '2',
                        3 => '3',
                        4 => '4',
                        5 => '5',
                    ])
                    ->icons([
                        1 => 'heroicon-m-star',
                        2 => 'heroicon-m-star',
                        3 => 'heroicon-m-star',
                        4 => 'heroicon-m-star',
                        5 => 'heroicon-m-star',
                    ])
                    ->colors([
                        1 => 'warning', // warning is usually amber/gold in Filament
                        2 => 'warning',
                        3 => 'warning',
                        4 => 'warning',
                        5 => 'warning',
                    ])
                    ->inline()
                    ->required(),
            ]);
    }
}
