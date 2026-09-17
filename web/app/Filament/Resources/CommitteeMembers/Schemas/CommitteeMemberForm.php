<?php

namespace App\Filament\Resources\CommitteeMembers\Schemas;

use App\Filament\Support\TranslatableTabs;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CommitteeMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Základní údaje')
                    ->columns(1)
                    ->components([
                        TextInput::make('name')
                            ->label('Jméno')
                            ->required(),
                        TextInput::make('email')
                            ->label('E-mail')
                            ->email()
                            ->required(),
                        FileUpload::make('photo')
                            ->label('Fotografie')
                            ->helperText('Nepovinné — bez fotografie se zobrazí zástupná ikona.')
                            ->image()
                            ->disk('public')
                            ->directory('committee-members')
                            ->columnSpanFull(),
                        TextInput::make('sort_order')
                            ->label('Pořadí')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ]),
                Section::make('Obsah')
                    ->description('Veškerý překladatelný text na jednom místě — jeden přepínač jazyka.')
                    ->columns(1)
                    ->components([
                        TranslatableTabs::make([
                            'role' => fn (string $locale) => TextInput::make('role')
                                ->label('Role ve výboru')
                                ->required($locale === 'cs'),
                        ]),
                    ]),
            ]);
    }
}
