<?php

namespace App\Filament\Resources\Articles\Schemas;

use App\Filament\Resources\ArticleCategories\Schemas\ArticleCategoryForm;
use App\Filament\Support\TranslatableTabs;
use App\Models\ArticleCategory;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Základní údaje')
                    ->columns(1)
                    ->components([
                        TextInput::make('slug_cs')
                            ->label('Slug (CZ)')
                            ->helperText('Generuje se automaticky z titulku při založení. Needituj bez rozmyslu, pokud je článek už publikovaný — mění se tím URL.')
                            ->required(),
                        TextInput::make('slug_en')
                            ->label('Slug (EN)')
                            ->helperText('Stejné pravidlo jako u CZ slugu.')
                            ->required(),
                        Select::make('article_category_id')
                            ->label('Kategorie')
                            ->relationship('category', 'name')
                            ->getOptionLabelFromRecordUsing(fn (ArticleCategory $record) => $record->name)
                            ->searchable()
                            ->preload()
                            ->createOptionForm(ArticleCategoryForm::components()),
                        DateTimePicker::make('published_at')
                            ->label('Datum publikace')
                            ->helperText('Zobrazovaný český formát data se z tohoto data počítá automaticky.')
                            ->default(now()),
                        FileUpload::make('image')
                            ->label('Úvodní obrázek')
                            ->image()
                            ->disk('public')
                            ->directory('articles')
                            ->columnSpanFull(),
                    ]),
                Section::make('Obsah')
                    ->components([
                        TranslatableTabs::make([
                            'title' => fn (string $locale) => TextInput::make('title')
                                ->label('Titulek')
                                ->required($locale === 'cs'),
                            'excerpt' => fn (string $locale) => Textarea::make('excerpt')
                                ->label('Perex (krátký úvodní text v přehledu)')
                                ->rows(3),
                            'body' => fn (string $locale) => RichEditor::make('body')
                                ->label('Obsah článku'),
                        ]),
                    ]),
                Section::make('Fotogalerie')
                    ->components([
                        FileUpload::make('gallery')
                            ->hiddenLabel()
                            ->image()
                            ->disk('public')
                            ->multiple()
                            ->reorderable()
                            ->directory('articles'),
                    ]),
                Section::make('SEO')
                    ->description('Nepovinné přepsání výchozích SEO hodnot — necháš-li prázdné, použije se automaticky vypočtený titulek/popis a úvodní obrázek článku.')
                    ->collapsed()
                    ->components([
                        TranslatableTabs::make([
                            'seo_title' => fn (string $locale) => TextInput::make('seo_title')
                                ->label('SEO titulek'),
                            'seo_description' => fn (string $locale) => Textarea::make('seo_description')
                                ->label('SEO popis (meta description)')
                                ->rows(2),
                        ]),
                        FileUpload::make('seo_image')
                            ->label('SEO obrázek (og:image)')
                            ->helperText('Necháš-li prázdné, použije se úvodní obrázek článku, jinak sitewide výchozí obrázek (Nastavení > SEO).')
                            ->image()
                            ->disk('public')
                            ->directory('seo'),
                    ]),
            ]);
    }
}
