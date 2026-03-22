<?php

namespace App\Filament\Resources\MealBoxPlanItems;

use App\Filament\Resources\MealBoxPlanItems\Pages\CreateMealBoxPlanItem;
use App\Filament\Resources\MealBoxPlanItems\Pages\EditMealBoxPlanItem;
use App\Filament\Resources\MealBoxPlanItems\Pages\ListMealBoxPlanItems;
use App\Filament\Resources\MealBoxPlanItems\Schemas\MealBoxPlanItemForm;
use App\Filament\Resources\MealBoxPlanItems\Tables\MealBoxPlanItemsTable;
use App\Models\MealBoxPlanItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MealBoxPlanItemResource extends Resource
{
    protected static ?string $model = MealBoxPlanItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
                protected static string | UnitEnum | null $navigationGroup = 'MealBox';

    protected static ?string $recordTitleAttribute = 'mealboxplanitem';

    public static function form(Schema $schema): Schema
    {
        return MealBoxPlanItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MealBoxPlanItemsTable::configure($table);
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
            'index' => ListMealBoxPlanItems::route('/'),
            'create' => CreateMealBoxPlanItem::route('/create'),
            'edit' => EditMealBoxPlanItem::route('/{record}/edit'),
        ];
    }
}
