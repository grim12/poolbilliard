<?php

namespace App\Filament\Resources\Notices\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class NoticeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug_cs')
                    ->label('Slug (CZ)')
                    ->helperText('Generuje se automaticky z titulku při založení. Needituj bez rozmyslu, pokud je zpráva už publikovaná — mění se tím URL.')
                    ->required(),
                TextInput::make('slug_en')
                    ->label('Slug (EN)')
                    ->helperText('Stejné pravidlo jako u CZ slugu.')
                    ->required(),
                DateTimePicker::make('published_at')
                    ->label('Datum publikace')
                    ->default(now()),
                Toggle::make('is_important')
                    ->label('Důležité')
                    ->helperText('Zvýrazní zprávu značkou "DŮLEŽITÉ" v přehledu.'),
                TranslatableTabs::make([
                    'title' => fn (string $locale) => TextInput::make('title')
                        ->label('Titulek')
                        ->required($locale === 'cs'),
                    'excerpt' => fn (string $locale) => Textarea::make('excerpt')
                        ->label('Perex (krátký úvodní text v přehledu)')
                        ->rows(3),
                    'body' => fn (string $locale) => RichEditor::make('body')
                        ->label('Obsah zprávy'),
                ])->columnSpanFull(),
            ]);
    }
}
