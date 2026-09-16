<?php

namespace App\Filament\Resources\JakZacitSections;

use App\Filament\Resources\JakZacitSections\Pages\CreateJakZacitSection;
use App\Filament\Resources\JakZacitSections\Pages\EditJakZacitSection;
use App\Filament\Resources\JakZacitSections\Pages\ListJakZacitSections;
use App\Filament\Resources\JakZacitSections\Schemas\JakZacitSectionForm;
use App\Filament\Resources\JakZacitSections\Tables\JakZacitSectionsTable;
use App\Models\JakZacitSection;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class JakZacitSectionResource extends Resource
{
    protected static ?string $model = JakZacitSection::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Správa Obsahu';

    protected static ?string $navigationLabel = 'Sekce Jak začít';

    protected static ?string $modelLabel = 'sekce Jak začít';

    protected static ?string $pluralModelLabel = 'sekce Jak začít';

    public static function form(Schema $schema): Schema
    {
        return JakZacitSectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JakZacitSectionsTable::configure($table);
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
            'index' => ListJakZacitSections::route('/'),
            'create' => CreateJakZacitSection::route('/create'),
            'edit' => EditJakZacitSection::route('/{record}/edit'),
        ];
    }
}
