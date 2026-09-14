<?php

namespace App\Filament\Resources\Hernas;

use App\Filament\Resources\Hernas\Pages\CreateHerna;
use App\Filament\Resources\Hernas\Pages\EditHerna;
use App\Filament\Resources\Hernas\Pages\ListHernas;
use App\Filament\Resources\Hernas\Schemas\HernaForm;
use App\Filament\Resources\Hernas\Tables\HernasTable;
use App\Models\Herna;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HernaResource extends Resource
{
    protected static ?string $model = Herna::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return HernaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HernasTable::configure($table);
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
            'index' => ListHernas::route('/'),
            'create' => CreateHerna::route('/create'),
            'edit' => EditHerna::route('/{record}/edit'),
        ];
    }
}
