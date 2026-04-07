<?php

namespace App\Filament\Resources\SmsPromos\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SmsPromoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(['pending' => 'Pending', 'sent' => 'Sent', 'failed' => 'Failed'])
                    ->default('pending')
                    ->required(),
            ]);
    }
}
