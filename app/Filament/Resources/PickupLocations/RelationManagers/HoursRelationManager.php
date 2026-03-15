<?php

namespace App\Filament\Resources\PickupLocations\RelationManagers;

use App\Filament\Resources\PickupLocations\PickupLocationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

// use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
// use Filament\Tables\Table;
use Filament\Forms;
use Filament\Forms\Form;
// use Filament\Actions\CreateAction;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class HoursRelationManager extends RelationManager
{
    protected static string $relationship = 'hours';

    protected static ?string $relatedResource = PickupLocationResource::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('day_of_week')
                ->options([
                    0 => 'Sunday',
                    1 => 'Monday',
                    2 => 'Tuesday',
                    3 => 'Wednesday',
                    4 => 'Thursday',
                    5 => 'Friday',
                    6 => 'Saturday',
                ])
                ->required(),

            Forms\Components\Select::make('hour_slot')
                ->options(array_combine(range(1,12), range(1,12)))
                ->required(),

            Forms\Components\Select::make('period')
                ->options([
                    'day' => 'Day',
                    'night' => 'Night'
                ])
                ->required(),

            Forms\Components\Toggle::make('is_active')
                ->default(true)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
        ->columns([
                Tables\Columns\TextColumn::make('day_of_week'),

                Tables\Columns\TextColumn::make('hour_slot'),

                Tables\Columns\TextColumn::make('period'),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
