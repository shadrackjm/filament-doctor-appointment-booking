<?php

namespace App\Livewire\Patient\Appointments;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Doctor;
use Livewire\Component;
use App\Models\Appointment;
use Livewire\WithPagination;

class MyAppointments extends Component
{

    use WithPagination;
    public $perPage = 5;
    public $search = '';

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
            'doctor_name' => $doctor->doctorUser->name,
            'doctor_email' => $doctor->doctorUser->email,
            'doctor_specialization' => $doctor->speciality->speciality_name,
            'cancelled_by' => $cancelled_by_details->name,
            'role' => $cancelled_by_details->role,
        ];
        // dd($appointmentEmailData);
        $this->sendAppointmentNotification($appointmentEmailData);

        $appointment->delete();

        // session()->flash('message','Appointment cancelled successfully');
        // if (auth()->user()->role == 0) {
        //     return $this->redirect('/my/appointments', navigate: true);
        // }

        // if (auth()->user()->role == 2) {
        //     return $this->redirect('/admin/appointments', navigate: true);
        // }

        // if (auth()->user()->role == 1) {
        //     return $this->redirect('/doctor/appointments', navigate: true);
        // }
    }

    public function start($appointment_id){
        $this->redirect('/live_consultation', navigate: true);
    }
    // public function sendAppointmentNotification($appointmentData)
    // {
    //     // Send to Admin
    //     $appointmentData['recipient_name'] = 'Admin Admin';
    //     $appointmentData['recipient_role'] = 'admin';
    //     Mail::to('admin@example.com')->send(new AppointmentCancelled($appointmentData));

    //     // Send to Doctor
    //     $appointmentData['recipient_name'] = $appointmentData['doctor_name'];
    //     $appointmentData['recipient_role'] = 'doctor';
    //     Mail::to($appointmentData['doctor_email'])->send(new AppointmentCancelled($appointmentData));

    //     // Send to Patient
    //     $appointmentData['recipient_name'] = $appointmentData['patient_name'];
    //     $appointmentData['recipient_role'] = 'patient';
    //     Mail::to($appointmentData['patient_email'])->send(new AppointmentCancelled($appointmentData));

    //     return 'Appointment notifications sent successfully!';
    // }
    public function render()
    {
        $user = auth()->user();
        return view('livewire.patient.appointments.my-appointments',[
            'all_appointments' => Appointment::search($this->search)
            ->with('patient','doctor')
            ->where('patient_id',$user->id)
            ->paginate($this->perPage)
        ]);
    }
}
