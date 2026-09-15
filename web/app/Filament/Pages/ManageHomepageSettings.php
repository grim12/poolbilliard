<?php

namespace App\Filament\Pages;

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
                        TextInput::make('featured_articles_button_text')
                            ->label('Text tlačítka')
                            ->required(),
                    ]),
                Section::make('Zprávy výkonného výboru')
                    ->columns(1)
                    ->components([
                        TextInput::make('notices_title')
                            ->label('Titulek')
                            ->required(),
                        TextInput::make('notices_subtitle')
                            ->label('Podnadpis')
                            ->required(),
                        TextInput::make('notices_button_text')
                            ->label('Text tlačítka')
                            ->required(),
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
                        TextInput::make('tournaments_title')
                            ->label('Titulek')
                            ->required(),
                        TextInput::make('tournaments_button_text')
                            ->label('Text tlačítka')
                            ->required(),
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
                        TextInput::make('leaderboards_title')
                            ->label('Titulek')
                            ->required(),
                        TextInput::make('leaderboards_subtitle')
                            ->label('Podnadpis')
                            ->required(),
                        TextInput::make('leaderboards_button_text')
                            ->label('Text tlačítka')
                            ->required(),
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
                        TextInput::make('partners_title')
                            ->label('Titulek')
                            ->required(),
                        TextInput::make('partners_button_text')
                            ->label('Text tlačítka')
                            ->required(),
                    ]),
            ]);
    }
}
