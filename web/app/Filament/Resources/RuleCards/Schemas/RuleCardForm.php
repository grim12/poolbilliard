<?php

namespace App\Filament\Resources\RuleCards\Schemas;

use App\Filament\Support\InternalLinkFields;
use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RuleCardForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Základní údaje')
                    ->columns(2)
                    ->components([
                        FileUpload::make('image')
                            ->label('Obrázek disciplíny')
                            ->helperText('Nepovinné — když je vyplněné, má přednost před ikonou níže.')
                            ->image()
                            ->disk('public')
                            ->directory('rule-cards')
                            ->columnSpanFull(),
                        TextInput::make('image_alt')
                            ->label('Alt text obrázku'),
                        TextInput::make('icon')
                            ->label('Ikona (heroicon)')
                            ->helperText('Použije se jen když není vyplněný obrázek výše, např. "book-open".'),
                        TextInput::make('button_url')
                            ->label('Ruční URL')
                            ->helperText('Použije se jen když níže není vybraná interní stránka ani záznam.')
                            ->url()
                            ->columnSpanFull(),
                        ...InternalLinkFields::make(),
                        TextInput::make('sort_order')
                            ->label('Pořadí')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ]),
                Section::make('Obsah')
                    ->description('Veškerý překladatelný text karty na jednom místě — jeden přepínač jazyka pro celou kartu.')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::make([
                            'title' => fn (string $locale) => TextInput::make('title')
                                ->label('Titulek')
                                ->required($locale === 'cs'),
                            'subtitle' => fn (string $locale) => TextInput::make('subtitle')
                                ->label('Podtitulek'),
                            'text' => fn (string $locale) => RichEditor::make('text')
                                ->label('Text')
                                ->required($locale === 'cs'),
                        ]),
                    ]),
            ]);
    }
}
