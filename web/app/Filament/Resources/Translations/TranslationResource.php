<?php

namespace App\Filament\Resources\Translations;

use App\Filament\Resources\Translations\Pages\CreateTranslation;
use App\Filament\Resources\Translations\Pages\EditTranslation;
use App\Filament\Resources\Translations\Pages\ListTranslations;
use App\Filament\Resources\Translations\Schemas\TranslationForm;
use App\Filament\Resources\Translations\Tables\TranslationsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\TranslationLoader\LanguageLine;
use UnitEnum;

/**
 * Manages the sitewide UI strings translated via `__('Nějaký český text')` in Blade — see
 * lang/en.json's replacement by Spatie\TranslationLoader\LanguageLine (App\Providers\
 * AppServiceProvider isn't involved; the package's own service provider swaps Laravel's
 * translation loader for one that checks this table first, falling back to any lang/{locale}.json
 * file for a key with no DB row). Only `group = '*'` rows exist in this app (plain __() calls,
 * no Laravel-style `trans('file.key')` namespacing), so the resource is scoped to that.
 *
 * `key` IS the literal Czech string a Blade view passes to __() — editing it only makes sense
 * if the matching template is updated to match, it isn't a free-form label (see TranslationForm).
 */
class TranslationResource extends Resource
{
    protected static ?string $model = LanguageLine::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLanguage;

    protected static string|UnitEnum|null $navigationGroup = 'Systém';

    protected static ?string $modelLabel = 'překlad';

    protected static ?string $pluralModelLabel = 'překlady';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('group', '*');
    }

    public static function form(Schema $schema): Schema
    {
        return TranslationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TranslationsTable::configure($table);
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
            'index' => ListTranslations::route('/'),
            'create' => CreateTranslation::route('/create'),
            'edit' => EditTranslation::route('/{record}/edit'),
        ];
    }
}
