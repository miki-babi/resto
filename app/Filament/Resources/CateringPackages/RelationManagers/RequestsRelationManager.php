<?php

namespace App\Filament\Resources\CateringPackages\RelationManagers;

use App\Filament\Resources\CateringRequests\CateringRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
// use Filament\Tables\Table;
use Filament\Forms;
use Filament\Forms\Form;
// use Filament\Actions\CreateAction;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;

class RequestsRelationManager extends RelationManager
{
    protected static string $relationship = 'requests';

    protected static ?string $relatedResource = CateringRequestResource::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
            TextInput::make('contact')->required(),
            Textarea::make('note'),
            Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'contacted' => 'Contacted',
                    'confirmed' => 'Confirmed',
                    'cancelled' => 'Cancelled',
                ])
                ->default('pending'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('contact')->sortable()->searchable(),
                TextColumn::make('status')->sortable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
