<?php

namespace App\Filament\Resources\FaqItems\Schemas;

use App\Filament\Resources\FaqGroups\Schemas\FaqGroupForm;
use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FaqItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TranslatableTabs::make('question', fn (string $locale) => TextInput::make('question')
                    ->required($locale === 'cs'))
                    ->columnSpanFull(),
                TranslatableTabs::make('answer', fn (string $locale) => RichEditor::make('answer')
                    ->required($locale === 'cs'))
                    ->columnSpanFull(),
                Select::make('groups')
                    ->label('Kde se zobrazí (skupiny)')
                    ->relationship('groups', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->createOptionForm(FaqGroupForm::components())
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
