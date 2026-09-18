<?php

namespace App\Filament\Resources\Myths\Pages;

use App\Filament\Resources\Myths\MythResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMyth extends EditRecord
{
    protected static string $resource = MythResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
