<?php

namespace App\Filament\Doctor\Resources\DoctorScheduleResource\Pages;

use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Doctor\Resources\DoctorScheduleResource;
use Illuminate\Support\Facades\Auth;

class CreateDoctorSchedule extends CreateRecord
{
    protected static string $resource = DoctorScheduleResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $data['doctor_id'] = Auth::user()->doctor->id;
        return static::getModel()::create($data);

    }
}
