<?php

namespace App\Filament\Resources\CompetitionSections\Pages;

use App\Filament\Resources\CompetitionSections\CompetitionSectionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCompetitionSections extends ListRecords
{
    protected static string $resource = CompetitionSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
