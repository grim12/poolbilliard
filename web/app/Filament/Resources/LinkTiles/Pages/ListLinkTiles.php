<?php

namespace App\Filament\Resources\LinkTiles\Pages;

use App\Filament\Resources\LinkTiles\LinkTileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLinkTiles extends ListRecords
{
    protected static string $resource = LinkTileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
