<?php

namespace App\Filament\Resources\Translations\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TranslationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('group')
                    ->default('*'),
                Textarea::make('key')
                    ->label('Český text (klíč)')
                    ->helperText('Musí přesně odpovídat textu, který šablona předává do __() — jinak se překlad nikde nepoužije. Needituj, pokud si nejsi jistý, že stejný text zůstává i v kódu.')
                    ->required()
                    ->rows(2)
                    ->unique(ignoreRecord: true),
                Textarea::make('text.cs')
                    ->label('Česky (přepis)')
                    ->helperText('Nepovinné — když necháš prázdné, použije se text z klíče výše.')
                    ->rows(2),
                Textarea::make('text.en')
                    ->label('Anglicky')
                    ->rows(2),
            ]);
    }
}
