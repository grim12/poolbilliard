<?php

namespace App\Filament\Pages;

use App\Filament\Support\TranslatableTabs;
use App\Settings\TurnajeSettings;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageTurnajeSettings extends SettingsPage
{
    protected static string $settings = TurnajeSettings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;

    protected static ?string $navigationLabel = 'Turnaje';

    protected static string|UnitEnum|null $navigationGroup = 'Stránky';

    protected static ?string $title = 'Nastavení stránky Turnaje';

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
                        ]),
                    ]),
            ]);
    }
}
