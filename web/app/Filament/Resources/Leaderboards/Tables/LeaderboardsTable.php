<?php

namespace App\Filament\Resources\Leaderboards\Tables;

use App\Models\Leaderboard;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LeaderboardsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Název žebříčku')
                    ->searchable(),
                IconColumn::make('featured')
                    ->label('Zvýrazněno')
                    ->boolean(),
                TextColumn::make('entries')
                    ->label('Počet hráčů')
                    ->state(fn (Leaderboard $record): int => count($record->entries ?? [])),
                TextColumn::make('sort_order')
                    ->label('Pořadí')
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
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
