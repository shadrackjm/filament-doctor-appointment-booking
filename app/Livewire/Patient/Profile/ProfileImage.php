<?php

namespace App\Livewire\Patient\Profile;

use App\Models\User;
use App\Models\Doctor;
use Livewire\Component;

class ProfileImage extends Component
{
    public $doctor_details;

    public function mount($id){
        $this->doctor_details = Doctor::find($id);

    }
    public function render()
    {
        return view('livewire.patient.profile.profile-image');
    }
}
