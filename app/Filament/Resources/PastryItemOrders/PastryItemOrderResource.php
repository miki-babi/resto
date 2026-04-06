<?php

namespace App\Filament\Resources\PastryItemOrders;

use App\Filament\Resources\PastryItemOrders\Pages\CreatePastryItemOrder;
use App\Filament\Resources\PastryItemOrders\Pages\EditPastryItemOrder;
use App\Filament\Resources\PastryItemOrders\Pages\ListPastryItemOrders;
use App\Filament\Resources\PastryItemOrders\RelationManagers\ItemsRelationManager;
use App\Filament\Resources\PastryItemOrders\Schemas\PastryItemOrderForm;
use App\Filament\Resources\PastryItemOrders\Tables\PastryItemOrdersTable;
use App\Models\PastryItemOrder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PastryItemOrderResource extends Resource
{
    protected static ?string $model = PastryItemOrder::class;

    protected static string|UnitEnum|null $navigationGroup = 'Pastry';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'pastryitemorder';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return PastryItemOrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PastryItemOrdersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
            // RelationManagers\OrderItemsRelationManager::class,
            ItemsRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPastryItemOrders::route('/'),
            'create' => CreatePastryItemOrder::route('/create'),
            'edit' => EditPastryItemOrder::route('/{record}/edit'),
        ];
    }
}
