<?php

namespace App\Filament\Resources\RecurringTournaments;

use App\Filament\Resources\RecurringTournaments\Pages\CreateRecurringTournament;
use App\Filament\Resources\RecurringTournaments\Pages\EditRecurringTournament;
use App\Filament\Resources\RecurringTournaments\Pages\ListRecurringTournaments;
use App\Filament\Resources\RecurringTournaments\Schemas\RecurringTournamentForm;
use App\Filament\Resources\RecurringTournaments\Tables\RecurringTournamentsTable;
use App\Models\RecurringTournament;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class RecurringTournamentResource extends Resource
{
    protected static ?string $model = RecurringTournament::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowPath;

    protected static string|UnitEnum|null $navigationGroup = 'Správa Obsahu';

    protected static ?string $modelLabel = 'pravidelný turnaj';

    protected static ?string $pluralModelLabel = 'pravidelné turnaje';

    public static function form(Schema $schema): Schema
    {
        return RecurringTournamentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RecurringTournamentsTable::configure($table);
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
            'index' => ListRecurringTournaments::route('/'),
            'create' => CreateRecurringTournament::route('/create'),
            'edit' => EditRecurringTournament::route('/{record}/edit'),
        ];
    }
}
