<?php

namespace App\Filament\Resources\RecurringTournaments\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RecurringTournamentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug_cs')
                    ->label('Slug (CZ)')
                    ->helperText('Generuje se automaticky z názvu při založení. Needituj bez rozmyslu, pokud je turnaj už publikovaný — mění se tím URL.')
                    ->required(),
                TextInput::make('slug_en')
                    ->label('Slug (EN)')
                    ->helperText('Stejné pravidlo jako u CZ slugu.')
                    ->required(),
                Select::make('herna_id')
                    ->label('Herna')
                    ->relationship('herna', 'name')
                    ->searchable()
                    ->preload()
                    ->helperText('Nepovinné — když je vyplněné, detail turnaje odkazuje na tuhle hernu.'),
                TextInput::make('url')
                    ->label('Kontakt na organizátora')
                    ->helperText('Nepovinný externí odkaz zobrazený jako tlačítko na detailu turnaje.')
                    ->url(),
                TranslatableTabs::make([
                    'title' => fn (string $locale) => TextInput::make('title')
                        ->required($locale === 'cs'),
                    'frequency' => fn (string $locale) => TextInput::make('frequency')
                        ->label('Frekvence')
                        ->helperText('Např. "Každá středa, 19:00".'),
                    'location_text' => fn (string $locale) => TextInput::make('location_text')
                        ->label('Místo konání'),
                    'description' => fn (string $locale) => RichEditor::make('description')
                        ->label('Popis turnaje')
                        ->helperText('Obsah zobrazený na detailu turnaje (pravidla, startovné...).'),
                ])->columnSpanFull(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Section::make('SEO')
                    ->description('Nepovinné přepsání výchozích SEO hodnot — necháš-li prázdné, použije se automaticky vypočtený titulek/popis.')
                    ->collapsed()
                    ->components([
                        TranslatableTabs::make([
                            'seo_title' => fn (string $locale) => TextInput::make('seo_title')
                                ->label('SEO titulek'),
                            'seo_description' => fn (string $locale) => Textarea::make('seo_description')
                                ->label('SEO popis (meta description)')
                                ->rows(2),
                        ]),
                        FileUpload::make('seo_image')
                            ->label('SEO obrázek (og:image)')
                            ->helperText('Necháš-li prázdné, použije se sitewide výchozí obrázek (Nastavení > SEO) — turnaj nemá vlastní obrázek.')
                            ->image()
                            ->disk('public')
                            ->directory('seo'),
                    ]),
            ]);
    }
}
