<?php

namespace App\Filament\Pages;

use App\Filament\Support\TranslatableTabs;
use App\Settings\KlubyHernySettings;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageKlubyHernySettings extends SettingsPage
{
    protected static string $settings = KlubyHernySettings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Kluby a herny';

    protected static string|UnitEnum|null $navigationGroup = 'Stránky';

    protected static ?string $title = 'Nastavení Kluby a Herny';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Kluby — hlavička')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::makeForSettings([
                            'kluby_title' => fn (string $locale) => TextInput::make('kluby_title')
                                ->label('Titulek')
                                ->required($locale === 'cs'),
                        ]),
                    ]),
                Section::make('Kluby — info panel')
                    ->description('Karta vedle mapy klubů.')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::makeForSettings([
                            'kluby_info_tag_text' => fn (string $locale) => TextInput::make('kluby_info_tag_text')
                                ->label('Štítek'),
                            'kluby_info_title' => fn (string $locale) => TextInput::make('kluby_info_title')
                                ->label('Titulek')
                                ->required($locale === 'cs'),
                            'kluby_info_text' => fn (string $locale) => Textarea::make('kluby_info_text')
                                ->label('Text')
                                ->rows(3),
                            'kluby_info_foot_text' => fn (string $locale) => Textarea::make('kluby_info_foot_text')
                                ->label('Text pod seznamem výhod')
                                ->rows(2),
                            'kluby_info_button_text' => fn (string $locale) => TextInput::make('kluby_info_button_text')
                                ->label('Text tlačítka')
                                ->required($locale === 'cs'),
                        ]),
                    ]),
                Section::make('Herny — hlavička')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::makeForSettings([
                            'herny_title' => fn (string $locale) => TextInput::make('herny_title')
                                ->label('Titulek')
                                ->required($locale === 'cs'),
                            'herny_subtitle' => fn (string $locale) => Textarea::make('herny_subtitle')
                                ->label('Podnadpis')
                                ->rows(2),
                        ]),
                    ]),
                Section::make('Herny — info panel')
                    ->description('Karta vedle mapy heren.')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::makeForSettings([
                            'herny_info_tag_text' => fn (string $locale) => TextInput::make('herny_info_tag_text')
                                ->label('Štítek'),
                            'herny_info_title' => fn (string $locale) => TextInput::make('herny_info_title')
                                ->label('Titulek')
                                ->required($locale === 'cs'),
                            'herny_info_text' => fn (string $locale) => Textarea::make('herny_info_text')
                                ->label('Text')
                                ->rows(3),
                            'herny_info_button_text' => fn (string $locale) => TextInput::make('herny_info_button_text')
                                ->label('Text tlačítka')
                                ->required($locale === 'cs'),
                        ]),
                    ]),
            ]);
    }
}
