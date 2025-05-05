<?php

namespace App\Livewire\Patient\Booking;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use Livewire\Component;
use App\Models\Appointment;
use Masmerise\Toaster\Toast;
use App\Models\DoctorSchedule;
use Masmerise\Toaster\Toaster;
use App\Mail\AppointmentCreated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class BookingComponent extends Component
{
    public $doctor_details;
    public $appointment_type;
    public $selectedDate;
    public $availableDates = [];
    public $timeSlots = [];
    public $recepient_doctor;
    public $recepient_admin;
    public function mount($id)
    {
        $this->doctor_details = Doctor::find($id);

        $this->fetchAvailableDates($this->doctor_details);

        $this->recepient_admin = User::where('role', 'admin')->first();
        $this->recepient_doctor = User::find($this->doctor_details->user->id);
        // dd($this->recepient_admin, $this->recepient_doctor);
    }

    public function fetchAvailableDates($doctor)
    {
        $schedules = DoctorSchedule::where('doctor_id', $doctor->id)
            ->get();
        $availability = [];
        foreach ($schedules as $schedule) {
            $dayOfWeek = $schedule->available_day; //1 - monday, 2 - tuesday, etc.
            $fromTime = Carbon::createFromFormat('H:i:s', $schedule->from);
            $toTime = Carbon::createFromFormat('H:i:s', $schedule->to);
            $availability[$dayOfWeek] = [
                'from' => $fromTime,
                'to' => $toTime,
            ];

        }

        $dates = [];
        $today = Carbon::today();
        for ($i = 0; $i < 365; $i++) { //1 year
            $date = $today->copy()->addDays($i);
            $dayOfWeek = $date->dayOfWeek;

            if (isset($availability[$dayOfWeek])) {
                $dates[] = $date->format('Y-m-d');
            }
        }

        $this->availableDates = $dates;

    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
        $this->fetchTimeSlots($date, $this->doctor_details);
    }

    public function fetchTimeSlots($date, $doctor)
    {
        $dayOfWeek = Carbon::parse($date)->dayOfWeek; //0 , 1... 5
        $carbonDate = Carbon::parse($date)->format('Y-m-d');
        $schedule = DoctorSchedule::where('doctor_id', $doctor->id)
            ->where('available_day', $dayOfWeek)
            ->first();
        // dd($schedule);
        if ($schedule) {
            $fromTime = Carbon::createFromFormat('H:i:s', $schedule->from);
            $toTime = Carbon::createFromFormat('H:i:s', $schedule->to);

            $slots = [];
            while ($fromTime->lessThan($toTime)) {

                $timeSlot = $fromTime->format('H:i:s');
                $appointmentExists = Appointment::where('doctor_id',  $doctor->id)
                    ->where('appointment_date', $carbonDate)
                    ->where('appointment_time', $timeSlot)
                    ->exists();
                    //check if the appointment exists for the selected date and time
                if (!$appointmentExists) {
                    $slots[] = $timeSlot;
                }

                $fromTime->addHour();
            }

            $this->timeSlots = $slots;
                    // dd($this->timeSlots);

        } else {
            $this->timeSlots = [];
        }
    }

    public function bookAppointment($slot){
        $patient = Patient::where('user_id', Auth::user()->id)->first();
        $carbonDate = Carbon::parse($this->selectedDate)->format('Y-m-d');
        $newAppointment = new Appointment();
        $newAppointment->patient_id = $patient->id;
        $newAppointment->doctor_id = $this->doctor_details->id;
        $newAppointment->appointment_date = $carbonDate;
        $newAppointment->appointment_time = $slot;
        $newAppointment->appointment_type = $this->appointment_type;
        $newAppointment->save();

        $appointmentEmailData = [
            'date' => $this->selectedDate,
            'time' => Carbon::parse($slot)->format('H:i A'),
            'location' => '123 Medical Street, Health City',
            'patient_name' => auth()->user()->name,
            'patient_email' => auth()->user()->email,
            'doctor_name' => $this->doctor_details->user->name,
            'doctor_email' => $this->doctor_details->user->email,
            'appointment_type' => $this->appointment_type == 'onsite' ? 'on-site' : 'live consultation',
            'doctor_specialization' => $this->doctor_details->speciality->name,
        ];
        $this->sendAppointmentNotification($appointmentEmailData);
        $recipient = [$this->recepient_doctor, $this->recepient_admin];

        Notification::make()
        ->title('New Appointment')
        ->body('A new Appointment with Dr.'.$this->doctor_details->user->name.' on '.$this->selectedDate.' at '.$slot.' was created!')
        ->actions([
            Action::make('Mark as Read')
                ->markAsRead(),
        ])
        ->sendToDatabase($recipient);
        // session()->flash('message','appointment with Dr.'.$this->doctor_details->doctorUser->name.' on '.$this->selectedDate.$slot.' was created!');
        Toaster::success('Appointment with Dr.'.$this->doctor_details->user->name.' on '.$this->selectedDate.' at '.$slot.' was created!');
        return $this->redirect('/my-appointments');
    }

    public function sendAppointmentNotification($appointmentData)
    {
        // Send to Admin
        $appointmentData['recipient_name'] = 'Admin Admin';
        $appointmentData['recipient_role'] = 'admin';
        Mail::to('shadrack@mballahrise.com')->queue(new AppointmentCreated($appointmentData));

        // Send to Doctor
        $appointmentData['recipient_name'] = $appointmentData['doctor_name'];
        $appointmentData['recipient_role'] = 'doctor';
        Mail::to($appointmentData['doctor_email'])->queue(new AppointmentCreated($appointmentData));

        // Send queue Patient
        $appointmentData['recipient_name'] = $appointmentData['patient_name'];
        $appointmentData['recipient_role'] = 'patient';
        Mail::to($appointmentData['patient_email'])->queue(new AppointmentCreated($appointmentData));

        return 'Appointment notifications sent successfully!';
    }
    public function render()
    {
        return view('livewire.patient.booking.booking-component');
    }
}
