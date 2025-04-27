<?php

namespace App\Livewire\Patient;

use App\Models\Specilaity;
use Livewire\Component;

class SpecialistCards extends Component
{
    public $specialist_cards;

    public function mount(){
        $this->specialist_cards = Specilaity::all();
    }
    public function render()
    {
        return view('livewire.patient.specialist-cards');
    }
}
