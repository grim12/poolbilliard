<?php

namespace App\Filament\Resources\FaqGroups\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class FaqGroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components(static::components());
    }

    /**
     * Exposed separately so FaqItemForm's ->createOptionForm() can reuse the exact same
     * fields when an admin adds a new group inline, without leaving the FAQ item form.
     *
     * @return array<int, Component>
     */
    public static function components(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
            TextInput::make('slug')
                ->required()
                ->unique(ignoreRecord: true)
                ->helperText('Použije se v kódu k vytažení položek pro danou stránku/sekci — needituj bez rozmyslu, pokud už je zapojený.'),
            TextInput::make('sort_order')
                ->required()
                ->numeric()
                ->default(0),
        ];
    }
}
