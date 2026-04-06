<?php

namespace App\Filament\Resources\MealBoxSubscriptionSkips\Schemas;

use App\Models\MealBoxSubscription;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class MealBoxSubscriptionSkipForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('meal_box_subscription_id')
                    ->label('Subscription')
                    ->options(MealBoxSubscription::all()->pluck('id', 'id'))
                    ->required(),
                DatePicker::make('skip_date')->required(),
            ]);
    }
}
