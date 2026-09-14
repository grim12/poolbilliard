<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use BackedEnum;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageGeneralSettings extends SettingsPage
{
    protected static string $settings = GeneralSettings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Nastavení';

    protected static ?string $title = 'Obecné nastavení';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Turnaje')
                    ->columns(1)
                    ->components([
                        TextInput::make('tournament_soon_threshold_days')
                            ->label('Za kolik dní se turnaj označí jako "blíží se"')
                            ->helperText('Datum turnaje se na kartě zvýrazní červeně, pokud začíná do tolika dní od dneška.')
                            ->required()
                            ->numeric()
                            ->minValue(1),
                    ]),
                Section::make('Nábor do klubů')
                    ->description('Použije se u klubu, který má vyplněný jen stav náboru (otevřeno/zavřeno), ale ne vlastní text.')
                    ->columns(1)
                    ->components([
                        RichEditor::make('recruitment_open_fallback_text')
                            ->label('Výchozí text — nábor otevřen')
                            ->required(),
                        RichEditor::make('recruitment_closed_fallback_text')
                            ->label('Výchozí text — nábor uzavřen')
                            ->required(),
                    ]),
            ]);
    }
}
