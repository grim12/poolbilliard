<?php

namespace App\Filament\Resources\Tournaments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TournamentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('url'),
                TextInput::make('tag_text'),
                Select::make('tag_color')
                    ->options([
                        'primary' => 'Primary',
                        'accent' => 'Accent',
                        'gold' => 'Gold',
                        'dark' => 'Dark',
                        'gray' => 'Gray',
                    ])
                    ->default('primary')
                    ->required(),
                TextInput::make('date_text')
                    ->label('Datum (text)'),
                TextInput::make('location_text')
                    ->label('Místo konání'),
                Toggle::make('badge')
                    ->label('Počítá se do žebříčku (medaile)'),
                Toggle::make('soon')
                    ->label('Blíží se (zvýraznit datum)'),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
