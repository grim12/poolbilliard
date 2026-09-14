<?php

namespace App\Filament\Resources\Hernas\Pages;

use App\Filament\Resources\Hernas\HernaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHernas extends ListRecords
{
    protected static string $resource = HernaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
