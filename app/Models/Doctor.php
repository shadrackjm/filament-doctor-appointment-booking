<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    /** @use HasFactory<\Database\Factories\DoctorFactory> */
    use HasFactory;

    public function appointments(){
        return $this->hasMany(Appointment::class);
    }

    public function patients(){
        return $this->hasMany(Patient::class);
    }

    public function user(){
        return $this->belongsTo(User::class);   
    }

    public function speciality(){
        return $this->belongsTo(Specilaity::class);
    }

   

}
