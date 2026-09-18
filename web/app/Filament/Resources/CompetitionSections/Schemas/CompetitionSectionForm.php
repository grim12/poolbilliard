<?php

namespace App\Filament\Resources\CompetitionSections\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CompetitionSectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Základní údaje')
                    ->columns(1)
                    ->components([
                        TextInput::make('anchor')
                            ->label('Kotva (#anchor)')
                            ->helperText('Krátký identifikátor pro odkaz v rychlé navigaci nahoře, např. "cpt" nebo "mcr-jednotlivcu" — nemusí vycházet z titulku, needituj bez rozmyslu (mění se tím odkaz).')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->alphaDash(),
                        TextInput::make('sort_order')
                            ->label('Pořadí')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ]),
                Section::make('Obsah')
                    ->description('Veškerý překladatelný text sekce na jednom místě — jeden přepínač jazyka pro celou sekci.')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::make([
                            'nav_label' => fn (string $locale) => TextInput::make('nav_label')
                                ->label('Text v rychlé navigaci')
                                ->helperText('Krátký text pro pilulku nahoře stránky — může se lišit od titulku.')
                                ->required($locale === 'cs'),
                            'eyebrow' => fn (string $locale) => TextInput::make('eyebrow')
                                ->label('Nadtitulek'),
                            'title' => fn (string $locale) => TextInput::make('title')
                                ->label('Titulek')
                                ->required($locale === 'cs'),
                            'body' => fn (string $locale) => RichEditor::make('body')
                                ->label('Hlavní text')
                                ->required($locale === 'cs'),
                            'aside' => fn (string $locale) => RichEditor::make('aside')
                                ->label('Postranní karta (nepovinné)')
                                ->helperText('Zobrazí se v pravém sloupci vedle hlavního textu.'),
                            'below' => fn (string $locale) => RichEditor::make('below')
                                ->label('Obsah pod sekcí (nepovinné)')
                                ->helperText('Zobrazí se přes celou šířku pod hlavním textem/postranní kartou.'),
                        ]),
                    ]),
            ]);
    }
}
