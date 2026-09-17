<?php

namespace App\Filament\Resources\Translations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Spatie\TranslationLoader\LanguageLine;

class TranslationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->label('Český text (klíč)')
                    ->searchable()
                    ->wrap()
                    ->limit(80),
                TextColumn::make('text_en')
                    ->label('Anglicky')
                    ->getStateUsing(fn (LanguageLine $record): string => $record->text['en'] ?? '—')
                    ->wrap()
                    ->limit(80),
                TextColumn::make('updated_at')
                    ->label('Upraveno')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('key')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
