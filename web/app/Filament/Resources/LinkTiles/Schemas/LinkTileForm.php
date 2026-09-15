<?php

namespace App\Filament\Resources\LinkTiles\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LinkTileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TranslatableTabs::make('title', fn (string $locale) => TextInput::make('title')
                    ->label('Titulek')
                    ->required($locale === 'cs')),
                TextInput::make('url')
                    ->label('Odkaz')
                    ->required(),
                FileUpload::make('image')
                    ->label('Obrázek na pozadí')
                    ->image()
                    ->disk('public')
                    ->directory('link-tiles')
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->label('Pořadí')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
