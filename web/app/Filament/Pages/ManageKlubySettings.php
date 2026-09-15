<?php

namespace App\Filament\Pages;

use App\Filament\Support\TranslatableTabs;
use App\Settings\KlubySettings;
use BackedEnum;
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
                Section::make('Hlavička')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::makeForSettings([
                            'title' => fn (string $locale) => TextInput::make('title')
                                ->label('Titulek')
                                ->required($locale === 'cs'),
                        ]),
                    ]),
                Section::make('Info panel')
                    ->description('Karta vedle mapy klubů.')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::makeForSettings([
                            'info_tag_text' => fn (string $locale) => TextInput::make('info_tag_text')
                                ->label('Štítek'),
                            'info_title' => fn (string $locale) => TextInput::make('info_title')
                                ->label('Titulek')
                                ->required($locale === 'cs'),
                            'info_text' => fn (string $locale) => Textarea::make('info_text')
                                ->label('Text')
                                ->rows(3),
                            'info_foot_text' => fn (string $locale) => Textarea::make('info_foot_text')
                                ->label('Text pod seznamem výhod')
                                ->rows(2),
                            'info_button_text' => fn (string $locale) => TextInput::make('info_button_text')
                                ->label('Text tlačítka')
                                ->required($locale === 'cs'),
                        ]),
                    ]),
            ]);
    }
}
