<?php

namespace App\Filament\Resources\Myths;

use App\Filament\Resources\Myths\Pages\CreateMyth;
use App\Filament\Resources\Myths\Pages\EditMyth;
use App\Filament\Resources\Myths\Pages\ListMyths;
use App\Filament\Resources\Myths\Schemas\MythForm;
use App\Filament\Resources\Myths\Tables\MythsTable;
use App\Models\Myth;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MythResource extends Resource
{
    protected static ?string $model = Myth::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static string|UnitEnum|null $navigationGroup = 'Správa Obsahu';

    protected static ?string $navigationLabel = 'Mýty a fakta';

    protected static ?string $modelLabel = 'mýtus';

    protected static ?string $pluralModelLabel = 'mýty a fakta';

    public static function form(Schema $schema): Schema
    {
        return MythForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MythsTable::configure($table);
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
            'index' => ListMyths::route('/'),
            'create' => CreateMyth::route('/create'),
            'edit' => EditMyth::route('/{record}/edit'),
        ];
    }
}
