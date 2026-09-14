<?php

namespace App\Filament\Resources\Clubs\Tables;

use App\Enums\Region;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ClubsTable
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
                TextColumn::make('members_count')
                    ->label('Členů')
                    ->counts('members'),
                IconColumn::make('recruitment_open')
                    ->boolean()
                    ->label('Nábor'),
            ])
            ->defaultSort('name')
            ->filters([
                SelectFilter::make('region')
                    ->label('Kraj')
                    ->options(Region::class),
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
