<?php

namespace App\Filament\Resources\RecurringTournaments\Pages;

use App\Filament\Resources\RecurringTournaments\RecurringTournamentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRecurringTournaments extends ListRecords
{
    protected static string $resource = RecurringTournamentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
