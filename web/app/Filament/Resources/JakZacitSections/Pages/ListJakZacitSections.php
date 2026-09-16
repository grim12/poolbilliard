<?php

namespace App\Filament\Resources\JakZacitSections\Pages;

use App\Filament\Resources\JakZacitSections\JakZacitSectionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJakZacitSections extends ListRecords
{
    protected static string $resource = JakZacitSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
