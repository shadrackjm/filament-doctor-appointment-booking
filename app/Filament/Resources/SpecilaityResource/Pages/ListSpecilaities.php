<?php

namespace App\Filament\Resources\SpecilaityResource\Pages;

use App\Filament\Resources\SpecilaityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSpecilaities extends ListRecords
{
    protected static string $resource = SpecilaityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
