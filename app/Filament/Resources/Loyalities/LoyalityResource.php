<?php

namespace App\Filament\Resources\Loyalities;

use App\Filament\Resources\Loyalities\Pages\CreateLoyality;
use App\Filament\Resources\Loyalities\Pages\EditLoyality;
use App\Filament\Resources\Loyalities\Pages\ListLoyalities;
use App\Filament\Resources\Loyalities\Schemas\LoyalityForm;
use App\Filament\Resources\Loyalities\Tables\LoyalitiesTable;
use App\Models\Loyality;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LoyalityResource extends Resource
{
    protected static ?string $model = Loyality::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return LoyalityForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LoyalitiesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLoyalities::route('/'),
            'create' => CreateLoyality::route('/create'),
            'edit' => EditLoyality::route('/{record}/edit'),
        ];
    }
}
