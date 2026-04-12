<?php

namespace App\Filament\Resources\FeedbackLinks;

use App\Filament\Resources\FeedbackLinks\Pages\CreateFeedbackLink;
use App\Filament\Resources\FeedbackLinks\Pages\EditFeedbackLink;
use App\Filament\Resources\FeedbackLinks\Pages\ListFeedbackLinks;
use App\Filament\Resources\FeedbackLinks\Schemas\FeedbackLinkForm;
use App\Filament\Resources\FeedbackLinks\Tables\FeedbackLinksTable;
use App\Models\FeedbackLink;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class FeedbackLinkResource extends Resource
{
    protected static ?string $model = FeedbackLink::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Link;

    protected static string|UnitEnum|null $navigationGroup = 'Feedback';

    protected static ?string $recordTitleAttribute = 'feedbacklink';

    public static function form(Schema $schema): Schema
    {
        return FeedbackLinkForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FeedbackLinksTable::configure($table);
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
            'index' => ListFeedbackLinks::route('/'),
            'create' => CreateFeedbackLink::route('/create'),
            'edit' => EditFeedbackLink::route('/{record}/edit'),
        ];
    }
}
