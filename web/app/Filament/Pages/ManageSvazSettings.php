<?php

namespace App\Filament\Pages;

use App\Filament\Support\TranslatableTabs;
use App\Settings\SvazSettings;
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

class ManageSvazSettings extends SettingsPage
{
    protected static string $settings = SvazSettings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static ?string $navigationLabel = 'Sportovní svaz';

    protected static string|UnitEnum|null $navigationGroup = 'Stránky';

    protected static ?string $title = 'Nastavení stránky Sportovní svaz';

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
                Section::make('Sloupec "Českomoravský billiardový svaz"')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::makeForSettings([
                            'info_title' => fn (string $locale) => TextInput::make('info_title')
                                ->label('Titulek')
                                ->required($locale === 'cs'),
                            'info_text' => fn (string $locale) => RichEditor::make('info_text')
                                ->label('Text')
                                ->required($locale === 'cs'),
                        ]),
                        TextInput::make('cmbs_website_url')
                            ->label('Odkaz — Oficiální web ČMBS')
                            ->required(),
                        TextInput::make('cmbs_bylaws_url')
                            ->label('Odkaz — Stanovy ČMBS')
                            ->required(),
                    ]),
                Section::make('"Potřebuji vyřídit …"')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::makeForSettings([
                            'tasks_title' => fn (string $locale) => TextInput::make('tasks_title')
                                ->label('Titulek')
                                ->required($locale === 'cs'),
                        ]),
                        Repeater::make('tasks')
                            ->hiddenLabel()
                            ->schema([
                                TextInput::make('text')
                                    ->label('Text (CZ)')
                                    ->required(),
                                TextInput::make('text_en')
                                    ->label('Text (EN)'),
                                TextInput::make('url')
                                    ->label('Odkaz')
                                    ->required(),
                            ])
                            ->columns(3)
                            ->reorderable()
                            ->itemLabel(fn (array $state): ?string => $state['text'] ?? null)
                            ->addActionLabel('Přidat odkaz'),
                    ]),
                Section::make('Sloupec "Výkonný výbor"')
                    ->description('Členové výboru se spravují samostatně (Správa Obsahu → Výkonný výbor). Zde jen úvodní texty sloupce.')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::makeForSettings([
                            'committee_title' => fn (string $locale) => TextInput::make('committee_title')
                                ->label('Titulek')
                                ->required($locale === 'cs'),
                            'committee_text' => fn (string $locale) => RichEditor::make('committee_text')
                                ->label('Text')
                                ->helperText('Např. kontaktní e-mail a číslo účtu, každé jako vlastní odstavec.')
                                ->required($locale === 'cs'),
                        ]),
                    ]),
                Section::make('Sekce "Dokumenty"')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::makeForSettings([
                            'documents_title' => fn (string $locale) => TextInput::make('documents_title')
                                ->label('Titulek')
                                ->required($locale === 'cs'),
                        ]),
                    ]),
                Section::make('Sekce "Zprávy výkonného výboru"')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::makeForSettings([
                            'notices_title' => fn (string $locale) => TextInput::make('notices_title')
                                ->label('Titulek')
                                ->required($locale === 'cs'),
                            'notices_subtitle' => fn (string $locale) => TextInput::make('notices_subtitle')
                                ->label('Podnadpis'),
                        ]),
                    ]),
            ]);
    }
}
