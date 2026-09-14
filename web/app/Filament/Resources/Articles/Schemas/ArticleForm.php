<?php

namespace App\Filament\Resources\Articles\Schemas;

use App\Filament\Resources\ArticleCategories\Schemas\ArticleCategoryForm;
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
                    ->columns(2)
                    ->components([
                        TextInput::make('title')
                            ->label('Titulek')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->helperText('Generuje se automaticky z titulku při založení. Needituj bez rozmyslu, pokud je článek už publikovaný — mění se tím URL.')
                            ->required(),
                        Select::make('article_category_id')
                            ->label('Kategorie')
                            ->relationship('category', 'name')
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
                        Textarea::make('excerpt')
                            ->label('Perex (krátký úvodní text v přehledu)')
                            ->rows(3)
                            ->columnSpanFull(),
                        RichEditor::make('body')
                            ->label('Obsah článku')
                            ->columnSpanFull(),
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
            ]);
    }
}
