<?php

namespace App\Filament\Resources\Hernas\Schemas;

use App\Enums\HernaStatus;
use App\Enums\Region;
use App\Enums\Sport;
use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HernaForm
{
    private const DAYS = ['Pondělí', 'Úterý', 'Středa', 'Čtvrtek', 'Pátek', 'Sobota', 'Neděle'];

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Základní údaje')
                    ->columns(1)
                    ->components([
                        TextInput::make('name')
                            ->label('Název')
                            ->required(),
                        TextInput::make('slug_cs')
                            ->label('Slug (CZ)')
                            ->helperText('Generuje se automaticky z názvu při založení. Needituj bez rozmyslu, pokud je herna už publikovaná — mění se tím URL.')
                            ->required(),
                        TextInput::make('slug_en')
                            ->label('Slug (EN)')
                            ->helperText('Stejné pravidlo jako u CZ slugu.')
                            ->required(),
                        Select::make('status')
                            ->label('Stav')
                            ->options(HernaStatus::class)
                            ->required(),
                    ]),
                Section::make('Obsah')
                    ->components([
                        TranslatableTabs::make([
                            'about_text' => fn (string $locale) => RichEditor::make('about_text')
                                ->label('O herně'),
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
                Section::make('Kontakt')
                    ->columns(1)
                    ->components([
                        TextInput::make('phone')
                            ->label('Telefon')
                            ->tel(),
                        TextInput::make('email')
                            ->label('E-mail')
                            ->email(),
                        TextInput::make('website')
                            ->label('Web')
                            ->url(),
                    ]),
                Section::make('Nabízené sporty')
                    ->components([
                        CheckboxList::make('sports')
                            ->hiddenLabel()
                            ->options(Sport::class)
                            ->columns(1),
                    ]),
                Section::make('Otevírací doba')
                    ->components([
                        Repeater::make('hours')
                            ->hiddenLabel()
                            ->schema([
                                Select::make('day')
                                    ->label('Den')
                                    ->options(array_combine(self::DAYS, self::DAYS))
                                    ->required(),
                                TextInput::make('text')
                                    ->label('Otevírací doba')
                                    ->placeholder('14:00–24:00 nebo Zavřeno'),
                            ])
                            ->columns(1)
                            ->defaultItems(0)
                            ->addActionLabel('Přidat den')
                            ->helperText('Nech prázdné, pokud otevírací dobu nechceš zobrazovat.'),
                    ]),
                Section::make('Fotogalerie')
                    ->components([
                        FileUpload::make('gallery')
                            ->hiddenLabel()
                            ->image()
                            ->disk('public')
                            ->multiple()
                            ->reorderable()
                            ->directory('herny'),
                    ]),
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
                            ->helperText('Necháš-li prázdné, použije se sitewide výchozí obrázek (Nastavení > SEO) — herna nemá vlastní úvodní fotku.')
                            ->image()
                            ->disk('public')
                            ->directory('seo'),
                    ]),
            ]);
    }
}
