<?php

namespace App\Filament\Resources\TournamentCategories\Pages;

use App\Filament\Resources\TournamentCategories\TournamentCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTournamentCategory extends EditRecord
{
    protected static string $resource = TournamentCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
