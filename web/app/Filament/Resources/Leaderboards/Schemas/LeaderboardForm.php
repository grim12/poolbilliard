<?php

namespace App\Filament\Resources\Leaderboards\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LeaderboardForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Základní údaje')
                    ->columns(2)
                    ->components([
                        TranslatableTabs::make('title', fn (string $locale) => TextInput::make('title')
                            ->label('Název žebříčku')
                            ->required($locale === 'cs'))
                            ->columnSpanFull(),
                        Toggle::make('featured')
                            ->label('Zvýraznit 1. místo')
                            ->helperText('Akcentní barva místo tmavě modré — použij jen pro hlavní celostátní žebříček.'),
                        TextInput::make('sort_order')
                            ->label('Pořadí')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ]),
                Section::make('Pořadí hráčů')
                    ->components([
                        Repeater::make('entries')
                            ->hiddenLabel()
                            ->schema([
                                TextInput::make('name')
                                    ->label('Jméno hráče / název týmu')
                                    ->required(),
                                TextInput::make('club')
                                    ->label('Klub')
                                    ->helperText('Nech prázdné u týmových žebříčků, kde je "hráčem" už celý tým.'),
                            ])
                            ->columns(2)
                            ->reorderable()
                            ->defaultItems(0)
                            ->maxItems(10)
                            ->addActionLabel('Přidat hráče')
                            ->helperText('Pořadí v seznamu = umístění v žebříčku (1. místo nahoře). Nejvýše 10 hráčů.'),
                    ]),
            ]);
    }
}
