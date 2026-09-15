<?php

namespace App\Filament\Pages;

use App\Filament\Support\TranslatableTabs;
use App\Settings\KalendarSettings;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageKalendarSettings extends SettingsPage
{
    protected static string $settings = KalendarSettings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $navigationLabel = 'Kalendář';

    protected static string|UnitEnum|null $navigationGroup = 'Stránky';

    protected static ?string $title = 'Nastavení stránky Kalendář';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Obsah')
                    ->description('Veškerý překladatelný text stránky na jednom místě — jeden přepínač jazyka pro celou stránku.')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::makeForSettings([
                            'title' => fn (string $locale) => TextInput::make('title')
                                ->label('Hlavička — titulek')
                                ->required($locale === 'cs'),
                            'subtitle' => fn (string $locale) => Textarea::make('subtitle')
                                ->label('Hlavička — podnadpis')
                                ->rows(2),
                            'recurring_tag_text' => fn (string $locale) => TextInput::make('recurring_tag_text')
                                ->label('Karta "Chceš si zahrát?" — štítek'),
                            'recurring_title' => fn (string $locale) => TextInput::make('recurring_title')
                                ->label('Karta "Chceš si zahrát?" — titulek')
                                ->required($locale === 'cs'),
                            'recurring_text' => fn (string $locale) => Textarea::make('recurring_text')
                                ->label('Karta "Chceš si zahrát?" — text')
                                ->rows(2),
                        ]),
                    ]),
                Section::make('Zdrojové kalendáře')
                    ->description('Seznam externích kalendářů v postranní kartě — titulek a podtitulek zvlášť pro češtinu a angličtinu, odkaz je společný pro obě jazykové verze.')
                    ->components([
                        Repeater::make('calendar_sources')
                            ->hiddenLabel()
                            ->schema([
                                TextInput::make('title')
                                    ->label('Titulek (CZ)')
                                    ->required(),
                                TextInput::make('title_en')
                                    ->label('Titulek (EN)'),
                                TextInput::make('subtitle')
                                    ->label('Podtitulek (CZ)'),
                                TextInput::make('subtitle_en')
                                    ->label('Podtitulek (EN)'),
                                TextInput::make('url')
                                    ->label('Odkaz')
                                    ->url()
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->reorderable()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->addActionLabel('Přidat kalendář'),
                    ]),
            ]);
    }
}
