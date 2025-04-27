<?php

namespace App\Filament\Doctor\Resources\PrescriptionResource\Pages;

use App\Filament\Doctor\Resources\PrescriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrescription extends EditRecord
{
    protected static string $resource = PrescriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
