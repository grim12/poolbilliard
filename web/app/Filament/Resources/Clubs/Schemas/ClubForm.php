<?php

namespace App\Filament\Resources\Clubs\Schemas;

use App\Enums\Region;
use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClubForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Základní údaje')
                    ->columns(1)
                    ->components([
                        TextInput::make('name')
                            ->label('Krátký název')
                            ->required(),
                        TextInput::make('full_name')
                            ->label('Celý (oficiální) název'),
                        TextInput::make('slug_cs')
                            ->label('Slug (CZ)')
                            ->helperText('Generuje se automaticky z názvu při založení. Needituj bez rozmyslu, pokud už je klub publikovaný — mění se tím URL.')
                            ->required(),
                        TextInput::make('slug_en')
                            ->label('Slug (EN)')
                            ->helperText('Stejné pravidlo jako u CZ slugu.')
                            ->required(),
                        FileUpload::make('image')
                            ->label('Úvodní fotka')
                            ->image()
                            ->disk('public')
                            ->directory('clubs')
                            ->columnSpanFull(),
                    ]),
                Section::make('Obsah')
                    ->components([
                        TranslatableTabs::make([
                            'about_text' => fn (string $locale) => RichEditor::make('about_text')
                                ->label('O klubu'),
                            'recruitment_text' => fn (string $locale) => RichEditor::make('recruitment_text')
                                ->label('Vlastní text náboru (nepovinné)')
                                ->helperText('Když necháš prázdné, použije se výchozí text pro daný stav náboru (Nastavení > Nábor do klubů).'),
                        ]),
                    ]),
                Section::make('Adresa a poloha')
                    ->columns(1)
                    ->components([
                        TextInput::make('address')
                            ->label('Ulice a číslo'),
                        TextInput::make('city')
                            ->label('Město'),
                        Select::make('region')
                            ->label('Kraj')
                            ->options(Region::class),
                        TextInput::make('lat')
                            ->label('Zeměpisná šířka')
                            ->numeric(),
                        TextInput::make('lng')
                            ->label('Zeměpisná délka')
                            ->numeric(),
                    ]),
                Section::make('Ambasador')
                    ->columns(1)
                    ->components([
                        TextInput::make('ambassador_name')
                            ->label('Jméno'),
                        TextInput::make('ambassador_website')
                            ->label('Web')
                            ->url(),
                    ]),
                Section::make('Nábor')
                    ->columns(1)
                    ->components([
                        Toggle::make('recruitment_open')
                            ->label('Nábor otevřen')
                            ->default(true),
                    ]),
                Section::make('SEO')
                    ->description('Nepovinné přepsání výchozích SEO hodnot — necháš-li prázdné, použije se automaticky vypočtený titulek/popis a úvodní fotka klubu.')
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
                            ->helperText('Necháš-li prázdné, použije se úvodní fotka klubu, jinak sitewide výchozí obrázek (Nastavení > SEO).')
                            ->image()
                            ->disk('public')
                            ->directory('seo'),
                    ]),
                Section::make('Členové klubu')
                    ->components([
                        Repeater::make('members')
                            ->relationship('members')
                            ->orderColumn('sort_order')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Jméno')
                                    ->required(),
                                FileUpload::make('photo')
                                    ->label('Fotka')
                                    ->image()
                                    ->disk('public')
                                    ->directory('club-members'),
                            ])
                            ->columns(1)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->addActionLabel('Přidat člena'),
                    ]),
            ]);
    }
}
