<?php

namespace App\Filament\Resources\ArticleCategories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

class ArticleCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components(static::components());
    }

    /**
     * Exposed separately so ArticleForm's ->createOptionForm() can reuse the exact same
     * fields when an admin adds a new category inline, without leaving the article form.
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
                    'gold-light' => 'Gold Light',
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
