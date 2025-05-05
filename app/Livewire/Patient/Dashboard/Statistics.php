<?php

namespace App\Livewire\Patient\Dashboard;

use Livewire\Component;

class Statistics extends Component
{
    public $upcoming_appointments_count = 0;
    public $complete_appointments_count = 0;

    public function mount()
    {
        $this->upcoming_appointments_count = auth()->user()->patient->appointments()
            ->where('status', 'complete')
            ->orWhere('status', 'in-complete')
            ->where('appointment_date', '>=', now()->format('Y-m-d'))
            // ->where('appointment_time', '>=', now()->format('H:i:s a'))
            ->count();
        $this->complete_appointments_count = auth()->user()->patient->appointments()
            ->where('status', 'complete')
            ->orWhere('status', 'in-complete')
            ->where('appointment_date', '<=', now()->format('Y-m-d'))
            // ->where('appointment_time', '<=', now()->format('H:i:s'))
            ->count();
    }
    public function render()
    {
        return view('livewire.patient.dashboard.statistics');
    }
}
