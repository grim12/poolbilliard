<?php

namespace App\Filament\Resources\RuleCards\Pages;

use App\Filament\Resources\RuleCards\RuleCardResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRuleCard extends EditRecord
{
    protected static string $resource = RuleCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
