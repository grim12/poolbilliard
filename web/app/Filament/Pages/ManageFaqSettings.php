<?php

namespace App\Filament\Pages;

use App\Filament\Support\TranslatableTabs;
use App\Settings\FaqSettings;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageFaqSettings extends SettingsPage
{
    protected static string $settings = FaqSettings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQuestionMarkCircle;

    protected static ?string $navigationLabel = 'FAQ';

    protected static string|UnitEnum|null $navigationGroup = 'Stránky';

    protected static ?string $title = 'Nastavení stránky FAQ';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('SEO')
                    ->description('Necháš-li prázdné, použije se automaticky vypočtený titulek/popis.')
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
