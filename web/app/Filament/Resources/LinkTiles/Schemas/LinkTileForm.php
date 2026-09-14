<?php

namespace App\Filament\Resources\LinkTiles\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LinkTileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Titulek')
                    ->required(),
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
