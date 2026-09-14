<?php

namespace App\Filament\Resources\Banners\Schemas;

use App\Enums\BannerColor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BannerForm
{
    /**
     * Button variant options — matches ui/'s button() macro `variant` param. Only meaningful
     * inside this Repeater (not a queryable/reusable column elsewhere), so a plain option list
     * instead of a dedicated enum class.
     */
    private const BUTTON_VARIANTS = [
        'solid' => 'Solid',
        'outline' => 'Outline',
        'link' => 'Link',
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Základní údaje')
                    ->columns(2)
                    ->components([
                        TextInput::make('title')
                            ->label('Titulek')
                            ->required()
                            ->columnSpanFull(),
                        RichEditor::make('text')
                            ->label('Text')
                            ->columnSpanFull(),
                        FileUpload::make('image')
                            ->label('Obrázek na pozadí')
                            ->image()
                            ->disk('public')
                            ->directory('banners')
                            ->columnSpanFull(),
                        TextInput::make('tag_text')
                            ->label('Štítek')
                            ->helperText('Např. "ZA 2 MĚSÍCE" nebo "Pro začátečníky" — nepovinné.'),
                        TextInput::make('meta_text')
                            ->label('Doplňkový text')
                            ->helperText('Např. "23. – 25. října 2026 · Bratislava" — nepovinné.'),
                        Select::make('color')
                            ->label('Barva')
                            ->options(BannerColor::class)
                            ->required()
                            ->default(BannerColor::Accent),
                        TextInput::make('sort_order')
                            ->label('Pořadí')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ]),
                Section::make('Tlačítka')
                    ->components([
                        Repeater::make('buttons')
                            ->hiddenLabel()
                            ->schema([
                                TextInput::make('text')
                                    ->label('Text tlačítka')
                                    ->required(),
                                TextInput::make('url')
                                    ->label('Odkaz')
                                    ->required(),
                                Select::make('variant')
                                    ->label('Varianta')
                                    ->options(self::BUTTON_VARIANTS)
                                    ->default('solid')
                                    ->required(),
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->maxItems(2)
                            ->addActionLabel('Přidat tlačítko')
                            ->helperText('Nejvýše 2 tlačítka, obě nepovinná.'),
                    ]),
            ]);
    }
}
