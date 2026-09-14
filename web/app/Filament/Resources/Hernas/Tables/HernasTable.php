<?php

namespace App\Filament\Resources\Hernas\Tables;

use App\Enums\HernaStatus;
use App\Enums\Region;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class HernasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('city')
                    ->searchable(),
                TextColumn::make('region')
                    ->badge(),
                TextColumn::make('status')
                    ->badge(),
            ])
            ->defaultSort('name')
            ->filters([
                SelectFilter::make('region')
                    ->label('Kraj')
                    ->options(Region::class),
                SelectFilter::make('status')
                    ->label('Stav')
                    ->options(HernaStatus::class),
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
