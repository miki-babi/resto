<?php

namespace App\Filament\Resources\MealBoxSubscriptions\Schemas;

use App\Models\Customer;
use App\Models\MealBoxPlan;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class MealBoxSubscriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextInput::make('customer_id')
                //     ->required()
                //     ->numeric(),
                // TextInput::make('meal_box_plan_id')
                //     ->required()
                //     ->numeric(),
                // DatePicker::make('start_date')
                //     ->required(),
                // DatePicker::make('end_date')
                //     ->required(),
                // Select::make('status')
                //     ->options(['active' => 'Active', 'paused' => 'Paused', 'cancelled' => 'Cancelled'])
                //     ->default('active')
                //     ->required(),
                // TimePicker::make('delivery_time'),
                // TextInput::make('address')
                //     ->required(),

                Select::make('customer_id')
                    ->label('Customer')
                    ->options(Customer::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('meal_box_plan_id')
                    ->label('Plan')
                    ->options(MealBoxPlan::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                DatePicker::make('start_date')->label('Start Date')
                    ->required()
                    ->default(now()), // today,
                DatePicker::make('end_date')->label('End Date')
                    ->required()
                    ->default(now()->addMonth()), // one month from today
                Select::make('status')
                    ->options(['active' => 'Active', 'paused' => 'Paused', 'cancelled' => 'Cancelled'])
                    ->required(),
                Select::make('delivery_time')->label('Delivery Time (Ethiopian Clock)')
                    ->required()
                    ->multiple() // This enables multiple selection
                    ->options([
                        '06:00' => '12:00 morning',  // 6 AM = 1 Ethiopian hour
                        '07:00' => '1:00 morning',
                        '08:00' => '2:00 morning',
                        '09:00' => '3:00 morning',
                        '10:00' => '4:00 morning',
                        '11:00' => '5:00 morning',
                        '12:00' => '6:00 afternoon', // 12 PM = 6 Ethiopian hour
                        '13:00' => '7:00 afternoon',
                        '14:00' => '8:00 afternoon',
                        '15:00' => '9:00 afternoon',
                        '16:00' => '10:00 afternoon',
                        '17:00' => '11:00 afternoon',
                        '18:00' => '12:00 evening',  // 6 PM = 12 Ethiopian hour
                        '19:00' => '1:00 evening', // optional if you want PM suffix
                    ]),
                TextInput::make('address')->required(),
            ]);
    }
}
