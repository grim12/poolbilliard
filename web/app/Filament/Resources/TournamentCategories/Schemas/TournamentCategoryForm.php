<?php

namespace App\Filament\Resources\TournamentCategories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

class TournamentCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components(static::components());
    }

    /**
     * Exposed separately (not just inlined in configure()) so TournamentForm's
     * ->createOptionForm() can reuse the exact same fields when an admin adds a new
     * category inline, without leaving the tournament form.
     *
     * @return array<int, Component>
     */
    public static function components(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->unique(ignoreRecord: true),
            Select::make('color')
                ->options([
                    'primary' => 'Primary',
                    'accent' => 'Accent',
                    'gold' => 'Gold',
                    'dark' => 'Dark',
                    'gray' => 'Gray',
                ])
                ->default('primary')
                ->required(),
            TextInput::make('sort_order')
                ->required()
                ->numeric()
                ->default(0),
        ];
    }
}
