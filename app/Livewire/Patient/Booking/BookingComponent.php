<?php

namespace App\Livewire\Patient\Booking;

use Carbon\Carbon;
use App\Models\Doctor;
use Livewire\Component;
use App\Models\Appointment;
use App\Models\DoctorSchedule;

class BookingComponent extends Component
{
    public $doctor_details;
    public $appointment_type;
    public $selectedDate;
    public $availableDates = [];
    public $timeSlots = [];
    public function mount($id)
    {
        $this->doctor_details = Doctor::find($id);

        $this->fetchAvailableDates($this->doctor_details);
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
        $carbonDate = Carbon::parse($this->selectedDate)->format('Y-m-d');
        $newAppointment = new Appointment();
        $newAppointment->patient_id = auth()->user()->id;
        $newAppointment->doctor_id = $this->doctor_details->id;
        $newAppointment->appointment_date = $carbonDate;
        $newAppointment->appointment_time = $slot;
        $newAppointment->appointment_type = $this->appointment_type;
        $newAppointment->save();

        // $appointmentEmailData = [
        //     'date' => $this->selectedDate,
        //     'time' => Carbon::parse($slot)->format('H:i A'),
        //     'location' => '123 Medical Street, Health City',
        //     'patient_name' => auth()->user()->name,
        //     'patient_email' => auth()->user()->email,
        //     'doctor_name' => $this->doctor_details->user->name,
        //     'doctor_email' => $this->doctor_details->user->email,
        //     'appointment_type' => $this->appointment_type == 0 ? 'on-site' : 'live consultation',
        //     'doctor_specialization' => $this->doctor_details->speciality->name,
        // ];
        // dd($appointmentEmailData);
        // $this->sendAppointmentNotification($appointmentEmailData);

        // session()->flash('message','appointment with Dr.'.$this->doctor_details->doctorUser->name.' on '.$this->selectedDate.$slot.' was created!');

        // return $this->redirect('/my/appointments',navigate: true);
    }
    public function render()
    {
        return view('livewire.patient.booking.booking-component');
    }
}
