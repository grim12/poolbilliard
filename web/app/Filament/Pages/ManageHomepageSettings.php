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
                Section::make('Obsah')
                    ->description('Veškerý překladatelný text homepage na jednom místě — jeden přepínač jazyka pro celou stránku, ne po sekcích.')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::makeForSettings([
                            'featured_articles_button_text' => fn (string $locale) => TextInput::make('featured_articles_button_text')
                                ->label('Novinky — text tlačítka')
                                ->required($locale === 'cs'),
                            'notices_title' => fn (string $locale) => TextInput::make('notices_title')
                                ->label('Zprávy VV — titulek')
                                ->required($locale === 'cs'),
                            'notices_subtitle' => fn (string $locale) => TextInput::make('notices_subtitle')
                                ->label('Zprávy VV — podnadpis')
                                ->required($locale === 'cs'),
                            'notices_button_text' => fn (string $locale) => TextInput::make('notices_button_text')
                                ->label('Zprávy VV — text tlačítka')
                                ->required($locale === 'cs'),
                            'tournaments_title' => fn (string $locale) => TextInput::make('tournaments_title')
                                ->label('Turnaje — titulek')
                                ->required($locale === 'cs'),
                            'tournaments_button_text' => fn (string $locale) => TextInput::make('tournaments_button_text')
                                ->label('Turnaje — text tlačítka')
                                ->required($locale === 'cs'),
                            'leaderboards_title' => fn (string $locale) => TextInput::make('leaderboards_title')
                                ->label('Žebříčky — titulek')
                                ->required($locale === 'cs'),
                            'leaderboards_subtitle' => fn (string $locale) => TextInput::make('leaderboards_subtitle')
                                ->label('Žebříčky — podnadpis')
                                ->required($locale === 'cs'),
                            'leaderboards_button_text' => fn (string $locale) => TextInput::make('leaderboards_button_text')
                                ->label('Žebříčky — text tlačítka')
                                ->required($locale === 'cs'),
                            'partners_title' => fn (string $locale) => TextInput::make('partners_title')
                                ->label('Partneři — titulek')
                                ->required($locale === 'cs'),
                            'partners_button_text' => fn (string $locale) => TextInput::make('partners_button_text')
                                ->label('Partneři — text tlačítka')
                                ->required($locale === 'cs'),
                        ]),
                    ]),
                Section::make('Propojené položky')
                    ->description('Bannery a dlaždice zobrazené na homepage. Prázdný výběr = daná sekce se na stránce vůbec nevypíše.')
                    ->columns(1)
                    ->components([
                        Select::make('banner_1_id')
                            ->label('Banner 1')
                            ->helperText('Zobrazí se hned pod sekcí Zprávy výkonného výboru.')
                            ->options(fn () => Banner::orderBy('sort_order')->get()->pluck('title', 'id'))
                            ->placeholder('— žádný banner —')
                            ->searchable(),
                        Select::make('banner_2_id')
                            ->label('Banner 2')
                            ->helperText('Zobrazí se hned pod sekcí Turnaje.')
                            ->options(fn () => Banner::orderBy('sort_order')->get()->pluck('title', 'id'))
                            ->placeholder('— žádný banner —')
                            ->searchable(),
                        Select::make('link_tile_ids')
                            ->label('Dlaždice')
                            ->helperText('Výběr a pořadí dlaždic.')
                            ->options(fn () => LinkTile::orderBy('sort_order')->get()->pluck('title', 'id'))
                            ->multiple()
                            ->reorderable(),
                    ]),
            ]);
    }
}
