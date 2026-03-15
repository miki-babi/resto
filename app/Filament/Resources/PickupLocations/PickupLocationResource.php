<?php

namespace App\Filament\Resources\PickupLocations;

use App\Filament\Resources\PickupLocations\Pages\CreatePickupLocation;
use App\Filament\Resources\PickupLocations\Pages\EditPickupLocation;
use App\Filament\Resources\PickupLocations\Pages\ListPickupLocations;
use App\Filament\Resources\PickupLocations\Schemas\PickupLocationForm;
use App\Filament\Resources\PickupLocations\Tables\PickupLocationsTable;
use App\Models\PickupLocation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PickupLocationResource extends Resource
{
    protected static ?string $model = PickupLocation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'pickuplocation';

    public static function form(Schema $schema): Schema
    {
        return PickupLocationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PickupLocationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
                RelationManagers\HoursRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPickupLocations::route('/'),
            'create' => CreatePickupLocation::route('/create'),
            'edit' => EditPickupLocation::route('/{record}/edit'),
        ];
    }
}
