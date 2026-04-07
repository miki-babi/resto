<?php

namespace App\Filament\Resources\TelegramConfigs;

use App\Filament\Resources\TelegramConfigs\Pages\CreateTelegramConfig;
use App\Filament\Resources\TelegramConfigs\Pages\EditTelegramConfig;
use App\Filament\Resources\TelegramConfigs\Pages\ListTelegramConfigs;
use App\Filament\Resources\TelegramConfigs\Schemas\TelegramConfigForm;
use App\Filament\Resources\TelegramConfigs\Tables\TelegramConfigsTable;
use App\Models\TelegramConfig;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TelegramConfigResource extends Resource
{
    protected static ?string $model = TelegramConfig::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'telegram config';

    public static function form(Schema $schema): Schema
    {
        return TelegramConfigForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TelegramConfigsTable::configure($table);
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
            'index' => ListTelegramConfigs::route('/'),
            'create' => CreateTelegramConfig::route('/create'),
            'edit' => EditTelegramConfig::route('/{record}/edit'),
        ];
    }
}
