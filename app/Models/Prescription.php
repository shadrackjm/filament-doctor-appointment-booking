<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    /** @use HasFactory<\Database\Factories\PrescriptionFactory> */
    use HasFactory;

    public function doctor(){
        return $this->belongsTo( Prescription::class);
    }

    public function patient(){
        return $this->belongsTo(Patient::class);
    }
}
