<?php

namespace App\Filament\Resources\LinkTiles\Pages;

use App\Filament\Resources\LinkTiles\LinkTileResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLinkTile extends EditRecord
{
    protected static string $resource = LinkTileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
