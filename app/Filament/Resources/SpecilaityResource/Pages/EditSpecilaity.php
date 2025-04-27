<?php

namespace App\Filament\Resources\SpecilaityResource\Pages;

use App\Filament\Resources\SpecilaityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpecilaity extends EditRecord
{
    protected static string $resource = SpecilaityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
