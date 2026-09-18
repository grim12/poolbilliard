<?php

namespace App\Filament\Resources\Hernas\Tables;

use App\Enums\HernaStatus;
use App\Enums\Region;
use App\Models\Herna;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
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
                // Quick one-click moderation for a pending public "Registrace herny" submission
                // (see RegistraceHernyController) — flips $status without opening the full edit
                // form. Content still needing a fix before approval goes through EditAction as
                // usual; these two just cover the common case.
                Action::make('approve')
                    ->label('Schválit')
                    ->icon(Heroicon::CheckCircle)
                    ->color('success')
                    ->visible(fn (Herna $record): bool => $record->status === HernaStatus::Pending)
                    ->requiresConfirmation()
                    ->action(fn (Herna $record) => $record->update(['status' => HernaStatus::Approved])),
                Action::make('reject')
                    ->label('Zamítnout')
                    ->icon(Heroicon::XCircle)
                    ->color('danger')
                    ->visible(fn (Herna $record): bool => $record->status === HernaStatus::Pending)
                    ->requiresConfirmation()
                    ->action(fn (Herna $record) => $record->update(['status' => HernaStatus::Rejected])),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
