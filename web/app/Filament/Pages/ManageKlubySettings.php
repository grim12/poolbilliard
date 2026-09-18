<?php

namespace App\Filament\Pages;

use App\Filament\Support\TranslatableTabs;
use App\Settings\KlubySettings;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageKlubySettings extends SettingsPage
{
    protected static string $settings = KlubySettings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Kluby';

    protected static string|UnitEnum|null $navigationGroup = 'Stránky';

    protected static ?string $title = 'Nastavení stránky Kluby';

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
                            'info_tag_text' => fn (string $locale) => TextInput::make('info_tag_text')
                                ->label('Info panel — štítek'),
                            'info_title' => fn (string $locale) => TextInput::make('info_title')
                                ->label('Info panel — titulek')
                                ->required($locale === 'cs'),
                            'info_text' => fn (string $locale) => Textarea::make('info_text')
                                ->label('Info panel — text')
                                ->rows(3),
                            'info_foot_text' => fn (string $locale) => Textarea::make('info_foot_text')
                                ->label('Info panel — text pod seznamem výhod')
                                ->rows(2),
                            'info_button_text' => fn (string $locale) => TextInput::make('info_button_text')
                                ->label('Info panel — text tlačítka')
                                ->required($locale === 'cs'),
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
