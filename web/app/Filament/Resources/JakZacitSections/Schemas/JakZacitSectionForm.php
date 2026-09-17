<?php

namespace App\Filament\Resources\JakZacitSections\Schemas;

use App\Filament\Support\InternalLinkFields;
use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class JakZacitSectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Základní údaje')
                    ->columns(2)
                    ->components([
                        TextInput::make('anchor')
                            ->label('Kotva (#anchor)')
                            ->helperText('Krátký identifikátor pro odkaz v rychlé navigaci nahoře, např. "zacatecnik". Zároveň musí odpovídat slugu skupiny FAQ (Taxonomie → Skupiny FAQ), ze které se čerpají otázky zobrazené v této sekci.')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->alphaDash(),
                        TextInput::make('sort_order')
                            ->label('Pořadí')
                            ->required()
                            ->numeric()
                            ->default(0),
                        TextInput::make('aside_panel_button_url')
                            ->label('Postranní panel — ruční URL')
                            ->helperText('Použije se jen když níže není vybraná interní stránka ani záznam. Nepovinné — panel může být i bez tlačítka (viz "Rodič").'),
                        ...InternalLinkFields::make('aside_panel_'),
                        TextInput::make('aside_card_button_url')
                            ->label('Postranní karta — ruční URL')
                            ->helperText('Použije se jen když níže není vybraná interní stránka ani záznam.'),
                        ...InternalLinkFields::make('aside_card_'),
                    ]),
                Section::make('Obsah')
                    ->description('Veškerý překladatelný text sekce na jednom místě — jeden přepínač jazyka pro celou sekci.')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::make([
                            'nav_label' => fn (string $locale) => TextInput::make('nav_label')
                                ->label('Text v rychlé navigaci')
                                ->helperText('Krátký text pro pilulku nahoře stránky, např. "Jsem začátečník".')
                                ->required($locale === 'cs'),
                            'eyebrow' => fn (string $locale) => TextInput::make('eyebrow')
                                ->label('Nadtitulek'),
                            'title' => fn (string $locale) => TextInput::make('title')
                                ->label('Titulek')
                                ->required($locale === 'cs'),
                            'intro' => fn (string $locale) => RichEditor::make('intro')
                                ->label('Úvodní text')
                                ->required($locale === 'cs'),
                            'aside_panel_title' => fn (string $locale) => TextInput::make('aside_panel_title')
                                ->label('Postranní panel — titulek')
                                ->required($locale === 'cs'),
                            'aside_panel_text' => fn (string $locale) => RichEditor::make('aside_panel_text')
                                ->label('Postranní panel — text')
                                ->required($locale === 'cs'),
                            'aside_panel_button_text' => fn (string $locale) => TextInput::make('aside_panel_button_text')
                                ->label('Postranní panel — text tlačítka')
                                ->helperText('Nepovinné — necháno prázdné panel zobrazí bez tlačítka.'),
                            'aside_card_eyebrow' => fn (string $locale) => TextInput::make('aside_card_eyebrow')
                                ->label('Postranní karta — nadtitulek'),
                            'aside_card_title' => fn (string $locale) => TextInput::make('aside_card_title')
                                ->label('Postranní karta — titulek')
                                ->required($locale === 'cs'),
                            'aside_card_text' => fn (string $locale) => RichEditor::make('aside_card_text')
                                ->label('Postranní karta — text')
                                ->required($locale === 'cs'),
                            'aside_card_button_text' => fn (string $locale) => TextInput::make('aside_card_button_text')
                                ->label('Postranní karta — text tlačítka')
                                ->required($locale === 'cs'),
                            'faq_title' => fn (string $locale) => TextInput::make('faq_title')
                                ->label('Titulek nad FAQ')
                                ->required($locale === 'cs'),
                        ]),
                    ]),
                Section::make('Kroky (postup)')
                    ->description('Číslovaný seznam kroků pod úvodním textem, každý s ikonou (heroicon). Text kroku může obsahovat odkaz.')
                    ->components([
                        Repeater::make('steps')
                            ->hiddenLabel()
                            ->schema([
                                TextInput::make('icon')
                                    ->label('Ikona (heroicon)')
                                    ->helperText('Např. "map-pin" nebo "trophy".')
                                    ->required(),
                                TextInput::make('title')
                                    ->label('Titulek (CZ)')
                                    ->helperText('Konvenčně včetně čísla kroku, např. "1. Najděte hernu ve svém okolí".')
                                    ->required(),
                                TextInput::make('title_en')
                                    ->label('Titulek (EN)'),
                                RichEditor::make('text')
                                    ->label('Text (CZ)'),
                                RichEditor::make('text_en')
                                    ->label('Text (EN)'),
                            ])
                            ->columns(2)
                            ->reorderable()
                            ->addActionLabel('Přidat krok'),
                    ]),
            ]);
    }
}
