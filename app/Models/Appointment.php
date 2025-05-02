<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentFactory> */
    use HasFactory;

    public function scopeSearch($query, $value){
        $query->where('appointment_date','like',"%{$value}%")
                ->orWhere('appointment_time','like',"%{$value}%")
            ->orWhereHas('doctor.user', function($q) use ($value) {
                $q->where('name', 'like', "%{$value}%");
            })
            ->orWhereHas('patient', function($q) use ($value) {
                $q->where('name', 'like', "%{$value}%");
            });
    }

    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }
    public function patient(){
        return $this->belongsTo(User::class, 'patient_id');
    }
}
