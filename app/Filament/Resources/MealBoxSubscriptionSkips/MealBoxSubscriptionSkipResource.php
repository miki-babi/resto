<?php

namespace App\Filament\Resources\MealBoxSubscriptionSkips;

use App\Filament\Resources\MealBoxSubscriptionSkips\Pages\CreateMealBoxSubscriptionSkip;
use App\Filament\Resources\MealBoxSubscriptionSkips\Pages\EditMealBoxSubscriptionSkip;
use App\Filament\Resources\MealBoxSubscriptionSkips\Pages\ListMealBoxSubscriptionSkips;
use App\Filament\Resources\MealBoxSubscriptionSkips\Schemas\MealBoxSubscriptionSkipForm;
use App\Filament\Resources\MealBoxSubscriptionSkips\Tables\MealBoxSubscriptionSkipsTable;
use App\Models\MealBoxSubscriptionSkip;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MealBoxSubscriptionSkipResource extends Resource
{
    protected static ?string $model = MealBoxSubscriptionSkip::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
                protected static string | UnitEnum | null $navigationGroup = 'MealBox';

    protected static ?string $recordTitleAttribute = 'mealboxsubscriptionskip';

    public static function form(Schema $schema): Schema
    {
        return MealBoxSubscriptionSkipForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MealBoxSubscriptionSkipsTable::configure($table);
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
            'index' => ListMealBoxSubscriptionSkips::route('/'),
            'create' => CreateMealBoxSubscriptionSkip::route('/create'),
            'edit' => EditMealBoxSubscriptionSkip::route('/{record}/edit'),
        ];
    }
}
