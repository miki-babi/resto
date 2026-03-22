<?php

namespace App\Filament\Resources\PastryPackageOrders;

use App\Filament\Resources\PastryPackageOrders\Pages\CreatePastryPackageOrder;
use App\Filament\Resources\PastryPackageOrders\Pages\EditPastryPackageOrder;
use App\Filament\Resources\PastryPackageOrders\Pages\ListPastryPackageOrders;
use App\Filament\Resources\PastryPackageOrders\Schemas\PastryPackageOrderForm;
use App\Filament\Resources\PastryPackageOrders\Tables\PastryPackageOrdersTable;
use App\Models\PastryPackageOrder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PastryPackageOrderResource extends Resource
{
    protected static ?string $model = PastryPackageOrder::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
        protected static string | UnitEnum | null $navigationGroup = 'Pastry';

    protected static ?string $recordTitleAttribute = 'pastrypackageorder';

    public static function form(Schema $schema): Schema
    {
        return PastryPackageOrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PastryPackageOrdersTable::configure($table);
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
            'index' => ListPastryPackageOrders::route('/'),
            'create' => CreatePastryPackageOrder::route('/create'),
            'edit' => EditPastryPackageOrder::route('/{record}/edit'),
        ];
    }
}
