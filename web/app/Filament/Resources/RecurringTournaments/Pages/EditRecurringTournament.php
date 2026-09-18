<?php

namespace App\Filament\Resources\RecurringTournaments\Pages;

use App\Filament\Resources\RecurringTournaments\RecurringTournamentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRecurringTournament extends EditRecord
{
    protected static string $resource = RecurringTournamentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
