<?php

namespace App\Filament\Resources\Tournaments\Schemas;

use App\Filament\Resources\TournamentCategories\Schemas\TournamentCategoryForm;
use App\Filament\Support\TranslatableTabs;
use App\Models\TournamentCategory;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TournamentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('url'),
                Select::make('tournament_category_id')
                    ->label('Kategorie')
                    ->relationship('category', 'name')
                    ->getOptionLabelFromRecordUsing(fn (TournamentCategory $record) => $record->name)
                    ->searchable()
                    ->preload()
                    ->createOptionForm(TournamentCategoryForm::components()),
                DatePicker::make('start_date')
                    ->label('Datum začátku')
                    ->helperText('Zobrazovaný text na webu se z tohoto data počítá automaticky (viz náhled ve sloupci "Datum" v tabulce).'),
                DatePicker::make('end_date')
                    ->label('Datum konce')
                    ->helperText('Nech prázdné u jednodenního turnaje.')
                    ->afterOrEqual('start_date'),
                TranslatableTabs::make([
                    'title' => fn (string $locale) => TextInput::make('title')
                        ->required($locale === 'cs'),
                    'location_text' => fn (string $locale) => TextInput::make('location_text')
                        ->label('Místo konání'),
                ])->columnSpanFull(),
                Toggle::make('badge')
                    ->label('Has Badge'),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
