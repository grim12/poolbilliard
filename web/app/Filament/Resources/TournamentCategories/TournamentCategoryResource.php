<?php

namespace App\Filament\Resources\TournamentCategories;

use App\Filament\Resources\TournamentCategories\Pages\CreateTournamentCategory;
use App\Filament\Resources\TournamentCategories\Pages\EditTournamentCategory;
use App\Filament\Resources\TournamentCategories\Pages\ListTournamentCategories;
use App\Filament\Resources\TournamentCategories\Schemas\TournamentCategoryForm;
use App\Filament\Resources\TournamentCategories\Tables\TournamentCategoriesTable;
use App\Models\TournamentCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TournamentCategoryResource extends Resource
{
    protected static ?string $model = TournamentCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|UnitEnum|null $navigationGroup = 'Taxonomie';

    protected static ?string $modelLabel = 'kategorie turnaje';

    protected static ?string $pluralModelLabel = 'kategorie turnajů';

    public static function form(Schema $schema): Schema
    {
        return TournamentCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TournamentCategoriesTable::configure($table);
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
            'index' => ListTournamentCategories::route('/'),
            'create' => CreateTournamentCategory::route('/create'),
            'edit' => EditTournamentCategory::route('/{record}/edit'),
        ];
    }
}
