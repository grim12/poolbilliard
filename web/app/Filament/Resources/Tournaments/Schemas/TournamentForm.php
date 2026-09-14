<?php

namespace App\Filament\Resources\Tournaments\Schemas;

use Filament\Forms\Components\DatePicker;
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
                DatePicker::make('start_date')
                    ->label('Datum začátku')
                    ->helperText('Zobrazovaný text na webu se z tohoto data počítá automaticky (viz náhled ve sloupci "Datum" v tabulce).'),
                DatePicker::make('end_date')
                    ->label('Datum konce')
                    ->helperText('Nech prázdné u jednodenního turnaje.')
                    ->afterOrEqual('start_date'),
                TextInput::make('location_text')
                    ->label('Místo konání'),
                Toggle::make('badge')
                    ->label('Has Badge'),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
