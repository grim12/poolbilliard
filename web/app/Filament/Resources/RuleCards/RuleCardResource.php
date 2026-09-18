<?php

namespace App\Filament\Resources\RuleCards;

use App\Filament\Resources\RuleCards\Pages\CreateRuleCard;
use App\Filament\Resources\RuleCards\Pages\EditRuleCard;
use App\Filament\Resources\RuleCards\Pages\ListRuleCards;
use App\Filament\Resources\RuleCards\Schemas\RuleCardForm;
use App\Filament\Resources\RuleCards\Tables\RuleCardsTable;
use App\Models\RuleCard;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class RuleCardResource extends Resource
{
    protected static ?string $model = RuleCard::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static string|UnitEnum|null $navigationGroup = 'Správa Obsahu';

    protected static ?string $navigationLabel = 'Pravidla disciplín';

    protected static ?string $modelLabel = 'karta pravidel';

    protected static ?string $pluralModelLabel = 'karty pravidel';

    public static function form(Schema $schema): Schema
    {
        return RuleCardForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RuleCardsTable::configure($table);
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
            'index' => ListRuleCards::route('/'),
            'create' => CreateRuleCard::route('/create'),
            'edit' => EditRuleCard::route('/{record}/edit'),
        ];
    }
}
