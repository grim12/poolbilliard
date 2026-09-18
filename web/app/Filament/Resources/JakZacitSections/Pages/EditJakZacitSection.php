<?php

namespace App\Filament\Resources\JakZacitSections\Pages;

use App\Filament\Resources\JakZacitSections\JakZacitSectionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJakZacitSection extends EditRecord
{
    protected static string $resource = JakZacitSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
