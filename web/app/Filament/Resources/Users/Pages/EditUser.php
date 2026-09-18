<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Nikdo si sám sobě nemůže smazat účet — jinak by se dal odříznout od adminu bez cesty zpět.
            DeleteAction::make()
                ->visible(fn () => $this->record->isNot(auth()->user())),
        ];
    }
}
