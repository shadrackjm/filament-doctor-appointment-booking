<?php

namespace App\Livewire\Patient\Appointments;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Doctor;
use Livewire\Component;
use App\Models\Appointment;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;
use App\Mail\AppointmentCancelled;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MyAppointments extends Component
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
        $cancelled_by_details = auth()->user();
        $appointment = Appointment::find($id);

        $patient = User::find($appointment->patient_id);
        $doctor = Doctor::find($appointment->doctor_id);

        $appointmentEmailData = [
            'date' => $appointment->appointment_date,
            'time' => Carbon::parse($appointment->appointment_time)->format('H:i A'),
            'location' => '123 Medical Street, Health City',
            'patient_name' => $patient->name,
            'patient_email' => $patient->email,
            'doctor_name' => $doctor->user->name,
            'doctor_email' => $doctor->user->email,
            'doctor_specialization' => $doctor->speciality->speciality_name,
            'cancelled_by' => $cancelled_by_details->name,
            'role' => $cancelled_by_details->role,
        ];
        $this->sendAppointmentNotification($appointmentEmailData);

        $appointment->delete();

        Toaster::success('Appointment cancelled successfully!');

        return $this->redirect('/my-appointments');
    }

    public function start($appointment_id){
        $this->redirect('/live_consultation', navigate: true);
    }
    public function sendAppointmentNotification($appointmentData)
    {
        // Send to Admin
        $appointmentData['recipient_name'] = 'Admin Admin';
        $appointmentData['recipient_role'] = 'admin';
        Mail::to('shadrack@mballahrise.com')->queue(new AppointmentCancelled($appointmentData));

        // Send to Doctor
        $appointmentData['recipient_name'] = $appointmentData['doctor_name'];
        $appointmentData['recipient_role'] = 'doctor';
        Mail::to($appointmentData['doctor_email'])->queue(new AppointmentCancelled($appointmentData));

        // Send to Patient
        $appointmentData['recipient_name'] = $appointmentData['patient_name'];
        $appointmentData['recipient_role'] = 'patient';
        Mail::to($appointmentData['patient_email'])->queue(new AppointmentCancelled($appointmentData));

        return 'Appointment notifications sent successfully!';
    }
    public function render()
    {
        return view('livewire.patient.appointments.my-appointments',[
            'all_appointments' => Appointment::search($this->search)
            ->with('patient','doctor')
            ->where('patient_id',$this->user->id)
            ->paginate($this->perPage)
        ]);
    }
}
