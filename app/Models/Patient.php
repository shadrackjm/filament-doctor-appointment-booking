<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    /** @use HasFactory<\Database\Factories\PatientFactory> */
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function appointments(){
        return $this->hasMany(Appointment::class);
    }
}
