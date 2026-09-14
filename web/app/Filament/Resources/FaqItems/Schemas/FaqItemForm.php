<?php

namespace App\Filament\Resources\FaqItems\Schemas;

use App\Filament\Resources\FaqGroups\Schemas\FaqGroupForm;
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
                TextInput::make('question')
                    ->required()
                    ->columnSpanFull(),
                RichEditor::make('answer')
                    ->required()
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
