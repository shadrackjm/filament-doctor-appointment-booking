<?php

namespace App\Livewire\Patient\Dashboard;

use App\Models\User;
use App\Models\Doctor;
use Livewire\Component;
use App\Models\Appointment;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\Auth;

class LatestAppointments extends Component
{
    use WithPagination;
    public $perPage = 5;
    public $search = '';
    public $user;

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function cancel($id){
        // $cancelled_by_details = ;
        $appointment = Appointment::find($id);

        $patient = User::find($appointment->patient_id);
        $doctor = Doctor::find($appointment->doctor_id);

        // $appointmentEmailData = [
        //     'date' => $appointment->appointment_date,
        //     'time' => Carbon::parse($appointment->appointment_time)->format('H:i A'),
        //     'location' => '123 Medical Street, Health City',
        //     'patient_name' => $patient->name,
        //     'patient_email' => $patient->email,
        //     'doctor_name' => $doctor->doctorUser->name,
        //     'doctor_email' => $doctor->doctorUser->email,
        //     'doctor_specialization' => $doctor->speciality->speciality_name,
        //     'cancelled_by' => $cancelled_by_details->name,
        //     'role' => $cancelled_by_details->role,
        // ];
        // // dd($appointmentEmailData);
        // $this->sendAppointmentNotification($appointmentEmailData);

        $appointment->delete();

        Toaster::success('Appointment cancelled successfully!');

        return $this->redirect('/my-appointments');
    }

    public function start($appointment_id){
        $this->redirect('/live_consultation', navigate: true);
    }
    public function render()
    {
        return view('livewire.patient.dashboard.latest-appointments',[
            'all_appointments' => Appointment::search($this->search)
            ->with('patient','doctor')
            ->where('patient_id',$this->user->id)
            ->latest()
            ->limit(5)
            ->paginate($this->perPage)
        ]);
    }
}
