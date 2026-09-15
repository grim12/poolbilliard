<?php

namespace App\Filament\Pages;

use App\Filament\Support\TranslatableTabs;
use App\Settings\PravidlaSettings;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManagePravidlaSettings extends SettingsPage
{
    protected static string $settings = PravidlaSettings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $navigationLabel = 'Pravidla';

    protected static string|UnitEnum|null $navigationGroup = 'Stránky';

    protected static ?string $title = 'Nastavení stránky Pravidla';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Hlavička')
                    ->description('Veškerý překladatelný text hlavičky na jednom místě — jeden přepínač jazyka pro celou hlavičku.')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::makeForSettings([
                            'hero_title' => fn (string $locale) => TextInput::make('hero_title')
                                ->label('Titulek')
                                ->required($locale === 'cs'),
                            'hero_text' => fn (string $locale) => Textarea::make('hero_text')
                                ->label('Text')
                                ->rows(2),
                        ]),
                    ]),
                Section::make('Sekce "Hrajete poprvé?"')
                    ->description('Nadpis a úvodní text nad seznamem mýtů.')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::makeForSettings([
                            'myths_title' => fn (string $locale) => TextInput::make('myths_title')
                                ->label('Titulek')
                                ->required($locale === 'cs'),
                            'myths_subtitle' => fn (string $locale) => RichEditor::make('myths_subtitle')
                                ->label('Text'),
                        ]),
                    ]),
                Section::make('Mýty a fakta')
                    ->description('Seznam mýtů — vždy zobrazen jako otázka, po rozkliknutí se ukáže správné pravidlo.')
                    ->components([
                        Repeater::make('myths')
                            ->hiddenLabel()
                            ->schema([
                                TextInput::make('myth_text')
                                    ->label('Mýtus (CZ)')
                                    ->required(),
                                TextInput::make('myth_text_en')
                                    ->label('Mýtus (EN)'),
                                RichEditor::make('correct_text')
                                    ->label('Správné pravidlo (CZ)')
                                    ->required(),
                                RichEditor::make('correct_text_en')
                                    ->label('Správné pravidlo (EN)'),
                            ])
                            ->columns(2)
                            ->reorderable()
                            ->itemLabel(fn (array $state): ?string => $state['myth_text'] ?? null)
                            ->addActionLabel('Přidat mýtus'),
                    ]),
                Section::make('Sekce "Pravidla podle disciplíny"')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::makeForSettings([
                            'rule_cards_title' => fn (string $locale) => TextInput::make('rule_cards_title')
                                ->label('Titulek')
                                ->required($locale === 'cs'),
                        ]),
                    ]),
            ]);
    }
}
