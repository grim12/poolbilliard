<?php

namespace App\Filament\Resources\CompetitionSections\Pages;

use App\Filament\Resources\CompetitionSections\CompetitionSectionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCompetitionSection extends EditRecord
{
    protected static string $resource = CompetitionSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
