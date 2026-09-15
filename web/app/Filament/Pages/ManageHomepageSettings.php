<?php

namespace App\Filament\Pages;

use App\Filament\Support\TranslatableTabs;
use App\Models\Banner;
use App\Models\LinkTile;
use App\Settings\HomepageSettings;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageHomepageSettings extends SettingsPage
{
    protected static string $settings = HomepageSettings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $navigationLabel = 'Homepage';

    protected static string|UnitEnum|null $navigationGroup = 'Stránky';

    protected static ?string $title = 'Nastavení homepage';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Novinky')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::makeForSettings([
                            'featured_articles_button_text' => fn (string $locale) => TextInput::make('featured_articles_button_text')
                                ->label('Text tlačítka')
                                ->required($locale === 'cs'),
                        ]),
                    ]),
                Section::make('Zprávy výkonného výboru')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::makeForSettings([
                            'notices_title' => fn (string $locale) => TextInput::make('notices_title')
                                ->label('Titulek')
                                ->required($locale === 'cs'),
                            'notices_subtitle' => fn (string $locale) => TextInput::make('notices_subtitle')
                                ->label('Podnadpis')
                                ->required($locale === 'cs'),
                            'notices_button_text' => fn (string $locale) => TextInput::make('notices_button_text')
                                ->label('Text tlačítka')
                                ->required($locale === 'cs'),
                        ]),
                    ]),
                Section::make('Banner 1')
                    ->description('Zobrazí se hned pod sekcí Zprávy výkonného výboru. Bez výběru se sekce vůbec nevypíše.')
                    ->columns(1)
                    ->components([
                        Select::make('banner_1_id')
                            ->label('Banner')
                            ->options(fn () => Banner::orderBy('sort_order')->get()->pluck('title', 'id'))
                            ->placeholder('— žádný banner —')
                            ->searchable(),
                    ]),
                Section::make('Turnaje')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::makeForSettings([
                            'tournaments_title' => fn (string $locale) => TextInput::make('tournaments_title')
                                ->label('Titulek')
                                ->required($locale === 'cs'),
                            'tournaments_button_text' => fn (string $locale) => TextInput::make('tournaments_button_text')
                                ->label('Text tlačítka')
                                ->required($locale === 'cs'),
                        ]),
                    ]),
                Section::make('Banner 2')
                    ->description('Zobrazí se hned pod sekcí Turnaje. Bez výběru se sekce vůbec nevypíše.')
                    ->columns(1)
                    ->components([
                        Select::make('banner_2_id')
                            ->label('Banner')
                            ->options(fn () => Banner::orderBy('sort_order')->get()->pluck('title', 'id'))
                            ->placeholder('— žádný banner —')
                            ->searchable(),
                    ]),
                Section::make('Žebříčky')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::makeForSettings([
                            'leaderboards_title' => fn (string $locale) => TextInput::make('leaderboards_title')
                                ->label('Titulek')
                                ->required($locale === 'cs'),
                            'leaderboards_subtitle' => fn (string $locale) => TextInput::make('leaderboards_subtitle')
                                ->label('Podnadpis')
                                ->required($locale === 'cs'),
                            'leaderboards_button_text' => fn (string $locale) => TextInput::make('leaderboards_button_text')
                                ->label('Text tlačítka')
                                ->required($locale === 'cs'),
                        ]),
                    ]),
                Section::make('Dlaždice')
                    ->description('Výběr a pořadí dlaždic zobrazených na homepage. Prázdný výběr = sekce se nevypíše.')
                    ->columns(1)
                    ->components([
                        Select::make('link_tile_ids')
                            ->hiddenLabel()
                            ->options(fn () => LinkTile::orderBy('sort_order')->get()->pluck('title', 'id'))
                            ->multiple()
                            ->reorderable(),
                    ]),
                Section::make('Partneři')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::makeForSettings([
                            'partners_title' => fn (string $locale) => TextInput::make('partners_title')
                                ->label('Titulek')
                                ->required($locale === 'cs'),
                            'partners_button_text' => fn (string $locale) => TextInput::make('partners_button_text')
                                ->label('Text tlačítka')
                                ->required($locale === 'cs'),
                        ]),
                    ]),
            ]);
    }
}
