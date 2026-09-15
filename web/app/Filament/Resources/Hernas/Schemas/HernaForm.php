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
                    ->columns(2)
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
                        TranslatableTabs::make('about_text', fn (string $locale) => RichEditor::make('about_text')
                            ->label('O herně'))
                            ->columnSpanFull(),
                    ]),
                Section::make('Adresa a poloha')
                    ->columns(2)
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
                    ->columns(3)
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
                            ->columns(3),
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
                            ->columns(2)
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
            ]);
    }
}
