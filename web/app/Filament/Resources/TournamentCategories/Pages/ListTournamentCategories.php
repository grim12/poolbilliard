<?php

namespace App\Filament\Resources\TournamentCategories\Pages;

use App\Filament\Resources\TournamentCategories\TournamentCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTournamentCategories extends ListRecords
{
    protected static string $resource = TournamentCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
