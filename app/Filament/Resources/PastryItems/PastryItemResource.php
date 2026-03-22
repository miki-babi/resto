<?php

namespace App\Filament\Resources\PastryItems;

use App\Filament\Resources\PastryItems\Pages\CreatePastryItem;
use App\Filament\Resources\PastryItems\Pages\EditPastryItem;
use App\Filament\Resources\PastryItems\Pages\ListPastryItems;
use App\Filament\Resources\PastryItems\Schemas\PastryItemForm;
use App\Filament\Resources\PastryItems\Tables\PastryItemsTable;
use App\Models\PastryItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PastryItemResource extends Resource
{
    protected static ?string $model = PastryItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
        protected static string | UnitEnum | null $navigationGroup = 'Pastry';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PastryItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PastryItemsTable::configure($table);
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
            'index' => ListPastryItems::route('/'),
            'create' => CreatePastryItem::route('/create'),
            'edit' => EditPastryItem::route('/{record}/edit'),
        ];
    }
}
