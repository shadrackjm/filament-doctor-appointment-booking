<?php

namespace App\Filament\Doctor\Resources\DoctorScheduleResource\Pages;

use App\Filament\Doctor\Resources\DoctorScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDoctorSchedules extends ListRecords
{
    protected static string $resource = DoctorScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
