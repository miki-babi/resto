<?php

namespace App\Filament\Resources\MealBoxPlans;

use App\Filament\Resources\MealBoxPlans\Pages\CreateMealBoxPlan;
use App\Filament\Resources\MealBoxPlans\Pages\EditMealBoxPlan;
use App\Filament\Resources\MealBoxPlans\Pages\ListMealBoxPlans;
use App\Filament\Resources\MealBoxPlans\Schemas\MealBoxPlanForm;
use App\Filament\Resources\MealBoxPlans\Tables\MealBoxPlansTable;
use App\Models\MealBoxPlan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MealBoxPlanResource extends Resource
{
    protected static ?string $model = MealBoxPlan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'mealboxplan';

                    protected static string | UnitEnum | null $navigationGroup = 'MealBox';


    public static function form(Schema $schema): Schema
    {
        return MealBoxPlanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MealBoxPlansTable::configure($table);
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
            'index' => ListMealBoxPlans::route('/'),
            'create' => CreateMealBoxPlan::route('/create'),
            'edit' => EditMealBoxPlan::route('/{record}/edit'),
        ];
    }
}
