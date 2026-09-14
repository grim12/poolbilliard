<?php

namespace App\Filament\Resources\Notices\Schemas;

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
                TextInput::make('title')
                    ->label('Titulek')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('slug')
                    ->helperText('Generuje se automaticky z titulku při založení. Needituj bez rozmyslu, pokud je zpráva už publikovaná — mění se tím URL.')
                    ->required(),
                DateTimePicker::make('published_at')
                    ->label('Datum publikace')
                    ->default(now()),
                Toggle::make('is_important')
                    ->label('Důležité')
                    ->helperText('Zvýrazní zprávu značkou "DŮLEŽITÉ" v přehledu.'),
                Textarea::make('excerpt')
                    ->label('Perex (krátký úvodní text v přehledu)')
                    ->rows(3)
                    ->columnSpanFull(),
                RichEditor::make('body')
                    ->label('Obsah zprávy')
                    ->columnSpanFull(),
            ]);
    }
}
