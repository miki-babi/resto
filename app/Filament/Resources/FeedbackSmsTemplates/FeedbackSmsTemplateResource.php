<?php

namespace App\Filament\Resources\FeedbackSmsTemplates;

use App\Filament\Resources\FeedbackSmsTemplates\Pages\CreateFeedbackSmsTemplate;
use App\Filament\Resources\FeedbackSmsTemplates\Pages\EditFeedbackSmsTemplate;
use App\Filament\Resources\FeedbackSmsTemplates\Pages\ListFeedbackSmsTemplates;
use App\Filament\Resources\FeedbackSmsTemplates\Schemas\FeedbackSmsTemplateForm;
use App\Filament\Resources\FeedbackSmsTemplates\Tables\FeedbackSmsTemplatesTable;
use App\Models\FeedbackSmsTemplate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FeedbackSmsTemplateResource extends Resource
{
    protected static ?string $model = FeedbackSmsTemplate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'feedbacksmstemplate';

    public static function form(Schema $schema): Schema
    {
        return FeedbackSmsTemplateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FeedbackSmsTemplatesTable::configure($table);
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
            'index' => ListFeedbackSmsTemplates::route('/'),
            'create' => CreateFeedbackSmsTemplate::route('/create'),
            'edit' => EditFeedbackSmsTemplate::route('/{record}/edit'),
        ];
    }
}
