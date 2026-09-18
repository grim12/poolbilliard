<?php

namespace App\Filament\Resources\CompetitionSections;

use App\Filament\Resources\CompetitionSections\Pages\CreateCompetitionSection;
use App\Filament\Resources\CompetitionSections\Pages\EditCompetitionSection;
use App\Filament\Resources\CompetitionSections\Pages\ListCompetitionSections;
use App\Filament\Resources\CompetitionSections\Schemas\CompetitionSectionForm;
use App\Filament\Resources\CompetitionSections\Tables\CompetitionSectionsTable;
use App\Models\CompetitionSection;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CompetitionSectionResource extends Resource
{
    protected static ?string $model = CompetitionSection::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Správa Obsahu';

    protected static ?string $navigationLabel = 'Sekce soutěží';

    protected static ?string $modelLabel = 'sekce soutěží';

    protected static ?string $pluralModelLabel = 'sekce soutěží';

    public static function form(Schema $schema): Schema
    {
        return CompetitionSectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompetitionSectionsTable::configure($table);
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
            'index' => ListCompetitionSections::route('/'),
            'create' => CreateCompetitionSection::route('/create'),
            'edit' => EditCompetitionSection::route('/{record}/edit'),
        ];
    }
}
