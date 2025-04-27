<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    /** @use HasFactory<\Database\Factories\DoctorScheduleFactory> */
    use HasFactory;

    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }
}
