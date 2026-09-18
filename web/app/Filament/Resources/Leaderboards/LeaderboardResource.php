<?php

namespace App\Filament\Resources\Leaderboards;

use App\Filament\Resources\Leaderboards\Pages\CreateLeaderboard;
use App\Filament\Resources\Leaderboards\Pages\EditLeaderboard;
use App\Filament\Resources\Leaderboards\Pages\ListLeaderboards;
use App\Filament\Resources\Leaderboards\Schemas\LeaderboardForm;
use App\Filament\Resources\Leaderboards\Tables\LeaderboardsTable;
use App\Models\Leaderboard;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class LeaderboardResource extends Resource
{
    protected static ?string $model = Leaderboard::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static string|UnitEnum|null $navigationGroup = 'Správa Obsahu';

    protected static ?string $modelLabel = 'žebříček';

    protected static ?string $pluralModelLabel = 'žebříčky';

    public static function form(Schema $schema): Schema
    {
        return LeaderboardForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeaderboardsTable::configure($table);
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
            'index' => ListLeaderboards::route('/'),
            'create' => CreateLeaderboard::route('/create'),
            'edit' => EditLeaderboard::route('/{record}/edit'),
        ];
    }
}
