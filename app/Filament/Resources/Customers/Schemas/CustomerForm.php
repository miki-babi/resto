<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->default(null),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->default(null),
                TextInput::make('telegram_user_id')
                    ->label('Telegram User ID')
                    ->default(null),
                TextInput::make('telegram_username')
                    ->label('Telegram Username')
                    ->default(null),
                TextInput::make('loyalty_points_balance')
                    ->label('Loyalty Points Balance')
                    ->numeric()
                    ->default(0)
                    ->disabled()
                    ->dehydrated(false),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
                TagsInput::make('tags.visit_behavior')
                    ->label('Visit Behavior Tags')
                    ->suggestions([
                        'Morning regular',
                        'Weekend visitor',
                        'Coffee lover',
                    ])
                    ->nestedRecursiveRules([
                        'string',
                        'max:255',
                    ])
                    ->helperText('Press Enter to add tags. You can also add your own custom tags.')
                    ->reorderable(),
                TagsInput::make('tags.order_behavior')
                    ->label('Order Behavior Tags')
                    ->suggestions([
                        'Quick buyer',
                        'High spender',
                        'Bulk orderer',
                        'Repeater',
                    ])
                    ->nestedRecursiveRules([
                        'string',
                        'max:255',
                    ])
                    ->helperText('Press Enter to add tags. You can also add your own custom tags.')
                    ->reorderable(),
                Toggle::make('is_blocked')
                    ->required(),
            ]);
    }
}
