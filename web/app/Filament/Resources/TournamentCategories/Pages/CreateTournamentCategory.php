<?php

namespace App\Filament\Resources\TournamentCategories\Pages;

use App\Filament\Resources\TournamentCategories\TournamentCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTournamentCategory extends CreateRecord
{
    protected static string $resource = TournamentCategoryResource::class;
}
