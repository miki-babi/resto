<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Resources\Pages\Pages\CreatePage;
use App\Filament\Resources\Pages\Pages\EditPage;
use App\Filament\Resources\Pages\Pages\ListPages;
use App\Filament\Resources\Pages\Schemas\PageForm;
use App\Filament\Resources\Pages\Tables\PagesTable;
use App\Models\Page;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PageResource extends Resource
{
    public const string HOME_ROUTE_NAME = 'home';

    public const string MENU_ROUTE_NAME = 'menu';

    public const string PAGE_ROUTE_NAME = 'page';

    public const string HOME_ROUTE_PATH = '/';

    public const string PAGE_ROUTE_PATH = '/{slug}';

    public const string PAGE_ROUTE_PATTERN = '^[A-Za-z0-9-]+$';

    protected static ?string $model = Page::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'WebFront';

    protected static ?string $recordTitleAttribute = 'pages';

    public static function homeRouteName(): string
    {
        return self::HOME_ROUTE_NAME;
    }

    public static function menuRouteName(): string
    {
        return self::MENU_ROUTE_NAME;
    }

    public static function pageRouteName(): string
    {
        return self::PAGE_ROUTE_NAME;
    }

    public static function homeRoutePath(): string
    {
        return self::HOME_ROUTE_PATH;
    }

    public static function pageRoutePath(): string
    {
        return self::PAGE_ROUTE_PATH;
    }

    public static function pageRoutePattern(): string
    {
        return self::PAGE_ROUTE_PATTERN;
    }

    public static function menuUrl(): string
    {
        return route(self::menuRouteName());
    }

    public static function pageUrl(string $slug): string
    {
        return route(self::pageRouteName(), ['slug' => $slug]);
    }

    public static function form(Schema $schema): Schema
    {
        return PageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PagesTable::configure($table);
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
            'index' => ListPages::route('/'),
            'create' => CreatePage::route('/create'),
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }
}
