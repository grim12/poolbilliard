<?php

namespace App\Filament\Resources\Documents\Schemas;

use App\Filament\Resources\DocumentCategories\Schemas\DocumentCategoryForm;
use App\Models\DocumentCategory;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Základní údaje')
                    ->columns(1)
                    ->components([
                        TextInput::make('name')
                            ->label('Název dokumentu')
                            ->helperText('Např. "Soutěžní řád 2026".')
                            ->required()
                            ->columnSpanFull(),
                        Select::make('document_category_id')
                            ->label('Kategorie')
                            ->relationship('category', 'name')
                            ->getOptionLabelFromRecordUsing(fn (DocumentCategory $record) => $record->name)
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm(DocumentCategoryForm::components()),
                        TextInput::make('year')
                            ->label('Rok')
                            ->helperText('Nechte prázdné pro evergreen dokumenty bez vazby na konkrétní rok (zobrazí se v roce "Obecné").')
                            ->numeric()
                            ->minValue(2000)
                            ->maxValue(2100),
                        FileUpload::make('file')
                            ->label('Soubor (PDF)')
                            ->required()
                            ->disk('public')
                            ->directory('documents')
                            ->acceptedFileTypes(['application/pdf'])
                            ->columnSpanFull(),
                        TextInput::make('sort_order')
                            ->label('Pořadí')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ]),
            ]);
    }
}
