<?php

namespace App\Filament\Resources\MealBoxSubscriptions;

use App\Filament\Resources\MealBoxSubscriptions\Pages\CreateMealBoxSubscription;
use App\Filament\Resources\MealBoxSubscriptions\Pages\EditMealBoxSubscription;
use App\Filament\Resources\MealBoxSubscriptions\Pages\ListMealBoxSubscriptions;
use App\Filament\Resources\MealBoxSubscriptions\Schemas\MealBoxSubscriptionForm;
use App\Filament\Resources\MealBoxSubscriptions\Tables\MealBoxSubscriptionsTable;
use App\Models\MealBoxSubscription;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MealBoxSubscriptionResource extends Resource
{
    protected static ?string $model = MealBoxSubscription::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

                    protected static string | UnitEnum | null $navigationGroup = 'MealBox';

    protected static ?string $recordTitleAttribute = 'mealboxsubscription';

    public static function form(Schema $schema): Schema
    {
        return MealBoxSubscriptionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MealBoxSubscriptionsTable::configure($table);
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
            'index' => ListMealBoxSubscriptions::route('/'),
            'create' => CreateMealBoxSubscription::route('/create'),
            'edit' => EditMealBoxSubscription::route('/{record}/edit'),
        ];
    }
}
