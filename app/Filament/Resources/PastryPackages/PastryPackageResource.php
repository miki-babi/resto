<?php

namespace App\Filament\Resources\PastryPackages;

use App\Filament\Resources\PastryPackages\Pages\CreatePastryPackage;
use App\Filament\Resources\PastryPackages\Pages\EditPastryPackage;
use App\Filament\Resources\PastryPackages\Pages\ListPastryPackages;
use App\Filament\Resources\PastryPackages\RelationManagers\ItemsRelationManager;
use App\Filament\Resources\PastryPackages\Schemas\PastryPackageForm;
use App\Filament\Resources\PastryPackages\Tables\PastryPackagesTable;
use App\Models\PastryPackage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PastryPackageResource extends Resource
{
    protected static ?string $model = PastryPackage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static string|UnitEnum|null $navigationGroup = 'Pastry';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PastryPackageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PastryPackagesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPastryPackages::route('/'),
            'create' => CreatePastryPackage::route('/create'),
            'edit' => EditPastryPackage::route('/{record}/edit'),
        ];
    }
}
