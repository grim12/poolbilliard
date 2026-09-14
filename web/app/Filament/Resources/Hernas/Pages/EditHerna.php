<?php

namespace App\Filament\Resources\Hernas\Pages;

use App\Filament\Resources\Hernas\HernaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHerna extends EditRecord
{
    protected static string $resource = HernaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
