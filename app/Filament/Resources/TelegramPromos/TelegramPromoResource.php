<?php

namespace App\Filament\Resources\TelegramPromos;

use App\Filament\Resources\TelegramPromos\Pages\CreateTelegramPromo;
use App\Filament\Resources\TelegramPromos\Pages\EditTelegramPromo;
use App\Filament\Resources\TelegramPromos\Pages\ListTelegramPromos;
use App\Filament\Resources\TelegramPromos\Schemas\TelegramPromoForm;
use App\Filament\Resources\TelegramPromos\Tables\TelegramPromosTable;
use App\Models\TelegramPromo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TelegramPromoResource extends Resource
{
    protected static ?string $model = TelegramPromo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Promotions';

    protected static ?string $recordTitleAttribute = 'telegrampromo';

    public static function form(Schema $schema): Schema
    {
        return TelegramPromoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TelegramPromosTable::configure($table);
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
            'index' => ListTelegramPromos::route('/'),
            'create' => CreateTelegramPromo::route('/create'),
            'edit' => EditTelegramPromo::route('/{record}/edit'),
        ];
    }
}
