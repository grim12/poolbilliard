<?php

namespace App\Filament\Resources\LinkTiles;

use App\Filament\Resources\LinkTiles\Pages\CreateLinkTile;
use App\Filament\Resources\LinkTiles\Pages\EditLinkTile;
use App\Filament\Resources\LinkTiles\Pages\ListLinkTiles;
use App\Filament\Resources\LinkTiles\Schemas\LinkTileForm;
use App\Filament\Resources\LinkTiles\Tables\LinkTilesTable;
use App\Models\LinkTile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LinkTileResource extends Resource
{
    protected static ?string $model = LinkTile::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return LinkTileForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LinkTilesTable::configure($table);
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
            'index' => ListLinkTiles::route('/'),
            'create' => CreateLinkTile::route('/create'),
            'edit' => EditLinkTile::route('/{record}/edit'),
        ];
    }
}
