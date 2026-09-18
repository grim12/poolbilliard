<?php

namespace App\Filament\Resources\FaqGroups;

use App\Filament\Resources\FaqGroups\Pages\CreateFaqGroup;
use App\Filament\Resources\FaqGroups\Pages\EditFaqGroup;
use App\Filament\Resources\FaqGroups\Pages\ListFaqGroups;
use App\Filament\Resources\FaqGroups\Schemas\FaqGroupForm;
use App\Filament\Resources\FaqGroups\Tables\FaqGroupsTable;
use App\Models\FaqGroup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class FaqGroupResource extends Resource
{
    protected static ?string $model = FaqGroup::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|UnitEnum|null $navigationGroup = 'Taxonomie';

    protected static ?string $modelLabel = 'skupina FAQ';

    protected static ?string $pluralModelLabel = 'skupiny FAQ';

    public static function form(Schema $schema): Schema
    {
        return FaqGroupForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FaqGroupsTable::configure($table);
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
            'index' => ListFaqGroups::route('/'),
            'create' => CreateFaqGroup::route('/create'),
            'edit' => EditFaqGroup::route('/{record}/edit'),
        ];
    }
}
