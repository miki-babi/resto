<?php

namespace App\Filament\Resources\CateringRequests;

use App\Filament\Resources\CateringRequests\Pages\CreateCateringRequest;
use App\Filament\Resources\CateringRequests\Pages\EditCateringRequest;
use App\Filament\Resources\CateringRequests\Pages\ListCateringRequests;
use App\Filament\Resources\CateringRequests\Schemas\CateringRequestForm;
use App\Filament\Resources\CateringRequests\Tables\CateringRequestsTable;
use App\Models\CateringRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CateringRequestResource extends Resource
{
    protected static ?string $model = CateringRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Catering';

    protected static ?string $recordTitleAttribute = 'cateringrequest';

    public static function form(Schema $schema): Schema
    {
        return CateringRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CateringRequestsTable::configure($table);
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
            'index' => ListCateringRequests::route('/'),
            'create' => CreateCateringRequest::route('/create'),
            'edit' => EditCateringRequest::route('/{record}/edit'),
        ];
    }
}
