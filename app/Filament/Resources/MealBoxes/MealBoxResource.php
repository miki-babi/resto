<?php

namespace App\Filament\Resources\MealBoxes;

use App\Filament\Resources\MealBoxes\Pages\CreateMealBox;
use App\Filament\Resources\MealBoxes\Pages\EditMealBox;
use App\Filament\Resources\MealBoxes\Pages\ListMealBoxes;
use App\Filament\Resources\MealBoxes\Schemas\MealBoxForm;
use App\Filament\Resources\MealBoxes\Tables\MealBoxesTable;
use App\Models\MealBox;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MealBoxResource extends Resource
{
    protected static ?string $model = MealBox::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArchiveBox;

    protected static string|UnitEnum|null $navigationGroup = 'MealBox';

    protected static ?string $recordTitleAttribute = 'mealbox';

    public static function form(Schema $schema): Schema
    {
        return MealBoxForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MealBoxesTable::configure($table);
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
            'index' => ListMealBoxes::route('/'),
            'create' => CreateMealBox::route('/create'),
            'edit' => EditMealBox::route('/{record}/edit'),
        ];
    }
}
