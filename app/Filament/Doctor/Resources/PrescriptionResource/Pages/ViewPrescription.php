<?php

namespace App\Filament\Doctor\Resources\PrescriptionResource\Pages;

use App\Filament\Doctor\Resources\PrescriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPrescription extends ViewRecord
{
    protected static string $resource = PrescriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
