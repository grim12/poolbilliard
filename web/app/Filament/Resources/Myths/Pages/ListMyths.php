<?php

namespace App\Filament\Resources\Myths\Pages;

use App\Filament\Resources\Myths\MythResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMyths extends ListRecords
{
    protected static string $resource = MythResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
