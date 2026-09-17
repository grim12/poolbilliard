<?php

namespace App\Filament\Pages;

use App\Filament\Support\TranslatableTabs;
use App\Settings\JakZacitSettings;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageJakZacitSettings extends SettingsPage
{
    protected static string $settings = JakZacitSettings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFlag;

    protected static ?string $navigationLabel = 'Jak začít';

    protected static string|UnitEnum|null $navigationGroup = 'Stránky';

    protected static ?string $title = 'Nastavení stránky Jak začít';

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
                Section::make('Kde začít — dlaždice')
                    ->description('4 dlaždice pro rychlý výběr nad sekcemi jednotlivých cest.')
                    ->components([
                        Repeater::make('feature_cards')
                            ->hiddenLabel()
                            ->schema([
                                TextInput::make('icon')
                                    ->label('Ikona (heroicon)')
                                    ->helperText('Např. "user-plus".')
                                    ->required(),
                                TextInput::make('title')
                                    ->label('Titulek (CZ)')
                                    ->required(),
                                TextInput::make('title_en')
                                    ->label('Titulek (EN)'),
                                TextInput::make('text')
                                    ->label('Text (CZ)'),
                                TextInput::make('text_en')
                                    ->label('Text (EN)'),
                                TextInput::make('link_text')
                                    ->label('Text odkazu (CZ)')
                                    ->required(),
                                TextInput::make('link_text_en')
                                    ->label('Text odkazu (EN)'),
                                TextInput::make('link_url')
                                    ->label('Cíl odkazu')
                                    ->helperText('Např. "#zacatecnik" — kotva jedné z níže spravovaných sekcí.')
                                    ->required(),
                            ])
                            ->columns(1)
                            ->reorderable()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->addActionLabel('Přidat dlaždici'),
                    ]),
                Section::make('Sekce "Připraveni začít?"')
                    ->description('Uzavírací sekce na konci stránky, nad formulářem poptávky.')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::makeForSettings([
                            'cta_title' => fn (string $locale) => TextInput::make('cta_title')
                                ->label('Titulek')
                                ->required($locale === 'cs'),
                            'cta_text' => fn (string $locale) => Textarea::make('cta_text')
                                ->label('Text')
                                ->rows(2),
                        ]),
                    ]),
                Section::make('Formulář poptávky')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::makeForSettings([
                            'match_form_eyebrow' => fn (string $locale) => TextInput::make('match_form_eyebrow')
                                ->label('Nadtitulek'),
                            'match_form_title' => fn (string $locale) => TextInput::make('match_form_title')
                                ->label('Titulek')
                                ->required($locale === 'cs'),
                            'match_form_text' => fn (string $locale) => RichEditor::make('match_form_text')
                                ->label('Text nad formulářem'),
                        ]),
                    ]),
                Section::make('SEO')
                    ->description('Nepovinné přepsání výchozích SEO hodnot — necháš-li prázdné, použije se automaticky vypočtený titulek/popis.')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::makeForSettings([
                            'seo_title' => fn (string $locale) => TextInput::make('seo_title')
                                ->label('SEO titulek'),
                            'seo_description' => fn (string $locale) => Textarea::make('seo_description')
                                ->label('SEO popis (meta description)')
                                ->rows(2),
                        ]),
                        FileUpload::make('seo_image')
                            ->label('SEO obrázek (og:image)')
                            ->helperText('Necháš-li prázdné, použije se sitewide výchozí obrázek (Nastavení > SEO).')
                            ->image()
                            ->disk('public')
                            ->directory('seo'),
                    ]),
            ]);
    }
}
