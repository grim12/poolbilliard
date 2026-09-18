<?php

namespace App\Filament\Resources\DocumentCategories\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

class DocumentCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components(static::components());
    }

    /**
     * Exposed separately so DocumentForm's ->createOptionForm() can reuse the exact same
     * fields when an admin adds a new category inline, without leaving the document form.
     *
     * @return array<int, Component>
     */
    public static function components(): array
    {
        return [
            TranslatableTabs::make([
                'name' => fn (string $locale) => TextInput::make('name')
                    ->required($locale === 'cs'),
            ]),
            TextInput::make('sort_order')
                ->required()
                ->numeric()
                ->default(0),
        ];
    }
}
