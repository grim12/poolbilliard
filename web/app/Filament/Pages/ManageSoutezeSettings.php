<?php

namespace App\Filament\Pages;

use App\Filament\Support\TranslatableTabs;
use App\Settings\SoutezeSettings;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageSoutezeSettings extends SettingsPage
{
    protected static string $settings = SoutezeSettings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $navigationLabel = 'Soutěže';

    protected static string|UnitEnum|null $navigationGroup = 'Stránky';

    protected static ?string $title = 'Nastavení stránky Soutěže';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Hlavička')
                    ->description('Veškerý překladatelný text hlavičky na jednom místě — jeden přepínač jazyka pro celou hlavičku.')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::makeForSettings([
                            'title' => fn (string $locale) => TextInput::make('title')
                                ->label('Titulek')
                                ->required($locale === 'cs'),
                            'subtitle' => fn (string $locale) => TextInput::make('subtitle')
                                ->label('Podnadpis'),
                            'text' => fn (string $locale) => Textarea::make('text')
                                ->label('Popisný text')
                                ->rows(4),
                        ]),
                    ]),
                Section::make('Statistiky')
                    ->description('Až 4 dlaždice v pravé části hlavičky (mřížka 2×2).')
                    ->components([
                        Repeater::make('stats')
                            ->hiddenLabel()
                            ->schema([
                                TextInput::make('value')
                                    ->label('Hodnota')
                                    ->helperText('Např. "6".')
                                    ->required(),
                                TextInput::make('label')
                                    ->label('Popisek (CZ)')
                                    ->required(),
                                TextInput::make('label_en')
                                    ->label('Popisek (EN)'),
                            ])
                            ->columns(1)
                            ->reorderable()
                            ->maxItems(4)
                            ->itemLabel(fn (array $state): ?string => $state['value'] ?? null)
                            ->addActionLabel('Přidat statistiku'),
                    ]),
            ]);
    }
}
