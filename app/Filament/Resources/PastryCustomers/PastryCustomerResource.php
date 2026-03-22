<?php

namespace App\Filament\Resources\PastryCustomers;

use App\Filament\Resources\PastryCustomers\Pages\CreatePastryCustomer;
use App\Filament\Resources\PastryCustomers\Pages\EditPastryCustomer;
use App\Filament\Resources\PastryCustomers\Pages\ListPastryCustomers;
use App\Filament\Resources\PastryCustomers\Schemas\PastryCustomerForm;
use App\Filament\Resources\PastryCustomers\Tables\PastryCustomersTable;
use App\Models\PastryCustomer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PastryCustomerResource extends Resource
{
    protected static ?string $model = PastryCustomer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

        protected static string | UnitEnum | null $navigationGroup = 'Pastry';

    protected static ?string $recordTitleAttribute = 'paystrycustomer';

    public static function form(Schema $schema): Schema
    {
        return PastryCustomerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PastryCustomersTable::configure($table);
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
            'index' => ListPastryCustomers::route('/'),
            'create' => CreatePastryCustomer::route('/create'),
            'edit' => EditPastryCustomer::route('/{record}/edit'),
        ];
    }
}
