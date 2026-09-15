<?php

namespace App\Filament\Resources\Myths\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MythForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('sort_order')
                    ->label('Pořadí')
                    ->required()
                    ->numeric()
                    ->default(0),
                TranslatableTabs::make([
                    'myth_text' => fn (string $locale) => TextInput::make('myth_text')
                        ->label('Mýtus')
                        ->required($locale === 'cs'),
                    'correct_text' => fn (string $locale) => RichEditor::make('correct_text')
                        ->label('Správné pravidlo')
                        ->required($locale === 'cs'),
                ])->columnSpanFull(),
            ]);
    }
}
