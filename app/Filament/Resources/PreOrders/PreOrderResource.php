<?php

namespace App\Filament\Resources\PreOrders;

use App\Filament\Resources\PreOrders\Pages\EditPreOrder;
use App\Filament\Resources\PreOrders\Pages\ListPreOrders;
use App\Filament\Resources\PreOrders\Pages\ViewPreOrder;
use App\Filament\Resources\PreOrders\Schemas\PreOrderForm;
use App\Filament\Resources\PreOrders\Tables\PreOrdersTable;
use App\Models\PreOrder;
use BackedEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

// use Filament\Schemas\Schema;

class PreOrderResource extends Resource
{
    protected static ?string $model = PreOrder::class;

    protected static string|UnitEnum|null $navigationGroup = 'Orders';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'order_number';

    public static function form(Schema $schema): Schema
    {
        return PreOrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PreOrdersTable::configure($table);
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
            'index' => ListPreOrders::route('/'),
            'edit' => EditPreOrder::route('/{record}/edit'),
            'view' => ViewPreOrder::route('/{record}'),
        ];
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('email'),
                TextEntry::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
