<?php

namespace App\Filament\Pages;

use App\Filament\Support\TranslatableTabs;
use App\Settings\GeneralSettings;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageGeneralSettings extends SettingsPage
{
    protected static string $settings = GeneralSettings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Nastavení';

    protected static string|UnitEnum|null $navigationGroup = 'Správa Obsahu';

    protected static ?int $navigationSort = 100;

    protected static ?string $title = 'Obecné nastavení';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Vzhled')
                    ->columns(1)
                    ->components([
                        Toggle::make('header_dark')
                            ->label('Tmavá hlavička')
                            ->helperText('Vypnuto = světlá hlavička (výchozí vzhled webu). Zapnuto = tmavá varianta hlavičky.'),
                    ]),
                Section::make('Turnaje')
                    ->columns(1)
                    ->components([
                        TextInput::make('tournament_soon_threshold_days')
                            ->label('Za kolik dní se turnaj označí jako "blíží se"')
                            ->helperText('Datum turnaje se na kartě zvýrazní červeně, pokud začíná do tolika dní od dneška.')
                            ->required()
                            ->numeric()
                            ->minValue(1),
                        TextInput::make('cmbs_tv_url')
                            ->label('Odkaz na ČMBS TV')
                            ->helperText('Cíl odkazu v poznámce "Přímé přenosy z turnajů sledujte na ČMBS TV" pod sekcí Turnaje. "#" znamená zatím bez reálného cíle.')
                            ->required(),
                    ]),
                Section::make('Nábor do klubů')
                    ->description('Použije se u klubu, který má vyplněný jen stav náboru (otevřeno/zavřeno), ale ne vlastní text.')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::makeForSettings([
                            'recruitment_open_fallback_text' => fn (string $locale) => RichEditor::make('recruitment_open_fallback_text')
                                ->label('Výchozí text — nábor otevřen')
                                ->required($locale === 'cs'),
                            'recruitment_closed_fallback_text' => fn (string $locale) => RichEditor::make('recruitment_closed_fallback_text')
                                ->label('Výchozí text — nábor uzavřen')
                                ->required($locale === 'cs'),
                        ]),
                    ]),
                Section::make('SEO')
                    ->description('Výchozí hodnoty pro celý web — jednotlivé stránky a záznamy (kluby, herny, turnaje, články...) je můžou ve svém vlastním SEO panelu přepsat.')
                    ->columns(1)
                    ->components([
                        TextInput::make('seo_title_suffix')
                            ->label('Přípona titulku stránek')
                            ->helperText('Připojí se za titulek každé stránky jako " — přípona" (např. "Kluby — Český Poolbilliard"). Homepage má vlastní kompletní titulek a příponu nedostává.')
                            ->required(),
                        FileUpload::make('seo_default_og_image')
                            ->label('Výchozí obrázek pro sdílení (og:image)')
                            ->helperText('Použije se všude tam, kde stránka ani záznam nemá vlastní obrázek.')
                            ->image()
                            ->disk('public')
                            ->directory('seo'),
                    ]),
            ]);
    }
}
