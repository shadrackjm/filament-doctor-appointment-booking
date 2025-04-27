<?php

namespace App\Filament\Doctor\Resources\AppointmentResource\Pages;

use App\Filament\Doctor\Resources\AppointmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;
}
