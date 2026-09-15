<?php

namespace App\Filament\Resources\Myths\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MythsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('myth_text')
                    ->label('Mýtus')
                    ->limit(60)
                    ->searchable(),
                TextColumn::make('sort_order')
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
