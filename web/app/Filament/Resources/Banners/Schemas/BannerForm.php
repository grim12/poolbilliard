<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Titulek')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('text')
                    ->label('Text')
                    ->rows(3)
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->label('Obrázek na pozadí')
                    ->image()
                    ->directory('banners')
                    ->columnSpanFull(),
                TextInput::make('tag_text')
                    ->label('Štítek')
                    ->helperText('Např. "ZA 2 MĚSÍCE" — nepovinné.'),
                TextInput::make('meta_text')
                    ->label('Doplňkový text')
                    ->helperText('Např. "23. – 25. října 2026 · Bratislava" — nepovinné.'),
                TextInput::make('button_text')
                    ->label('Text tlačítka')
                    ->required()
                    ->default('Detail akce'),
                TextInput::make('button_url')
                    ->label('Odkaz tlačítka')
                    ->required()
                    ->default('#'),
                TextInput::make('sort_order')
                    ->label('Pořadí')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
