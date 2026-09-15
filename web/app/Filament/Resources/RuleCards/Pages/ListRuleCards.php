<?php

namespace App\Filament\Resources\RuleCards\Pages;

use App\Filament\Resources\RuleCards\RuleCardResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRuleCards extends ListRecords
{
    protected static string $resource = RuleCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
