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
                TextInput::make('date_text')
                    ->label('Datum (text)')
                    ->helperText('Zobrazuje se na webu — může být rozsah, např. "12. – 13. září 2026".')
                    ->required(),
                DatePicker::make('start_date')
                    ->label('Datum začátku')
                    ->helperText('Skutečné datum pro výpočet "blíží se" (viz Nastavení) — nezobrazuje se na webu, tam je date_text.'),
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
