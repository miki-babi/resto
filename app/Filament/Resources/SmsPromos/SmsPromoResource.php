<?php

namespace App\Filament\Resources\SmsPromos;

use App\Filament\Resources\SmsPromos\Pages\CreateSmsPromo;
use App\Filament\Resources\SmsPromos\Pages\EditSmsPromo;
use App\Filament\Resources\SmsPromos\Pages\ListSmsPromos;
use App\Filament\Resources\SmsPromos\Schemas\SmsPromoForm;
use App\Filament\Resources\SmsPromos\Tables\SmsPromosTable;
use App\Models\SmsPromo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SmsPromoResource extends Resource
{
    protected static ?string $model = SmsPromo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Promotions';

    protected static ?string $recordTitleAttribute = 'smspromo';

    public static function form(Schema $schema): Schema
    {
        return SmsPromoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SmsPromosTable::configure($table);
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
            'index' => ListSmsPromos::route('/'),
            'create' => CreateSmsPromo::route('/create'),
            'edit' => EditSmsPromo::route('/{record}/edit'),
        ];
    }
}
