<?php

namespace App\Filament\Resources\FaqGroups\Pages;

use App\Filament\Resources\FaqGroups\FaqGroupResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFaqGroup extends CreateRecord
{
    protected static string $resource = FaqGroupResource::class;
}
