<?php

namespace App\Filament\Pages;

use App\Filament\Support\TranslatableTabs;
use App\Settings\HernySettings;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageHernySettings extends SettingsPage
{
    protected static string $settings = HernySettings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static ?string $navigationLabel = 'Herny';

    protected static string|UnitEnum|null $navigationGroup = 'Stránky';

    protected static ?string $title = 'Nastavení stránky Herny';

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
                            'subtitle' => fn (string $locale) => Textarea::make('subtitle')
                                ->label('Podnadpis')
                                ->rows(2),
                        ]),
                    ]),
                Section::make('Info panel')
                    ->description('Karta vedle mapy heren.')
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
                            'info_button_text' => fn (string $locale) => TextInput::make('info_button_text')
                                ->label('Text tlačítka')
                                ->required($locale === 'cs'),
                        ]),
                    ]),
            ]);
    }
}
