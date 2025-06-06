@component('mail::message')
# Appointment Confirmation

Dear {{ $appointmentData['recipient_name'] }},

An appointment has been Cancelled with the following details:

### Appointment Details:
- **Date:** {{ $appointmentData['date'] }}
- **Time:** {{ $appointmentData['time'] }}
- **Location:** {{ $appointmentData['location'] }}

### Patient Details:
- **Name:** {{ $appointmentData['patient_name'] }}
- **Email:** {{ $appointmentData['patient_email'] }}

### Doctor Details:
- **Name:** {{ $appointmentData['doctor_name'] }}
- **Specialization:** {{ $appointmentData['doctor_specialization'] }}

### Appointment Cancelled by:
- **Name:** {{ $appointmentData['cancelled_by'] }}
@if ($appointmentData['cancelled_by'] == 'doctor')
- **Role:** Doctor
@elseif($appointmentData['cancelled_by'] == 'admin')
- **Role:** Admin
@else
- **Role:** Patient
@endif


@if($appointmentData['role'] == 'admin')
## Admin Notification
You are receiving this email because an appointment has been scheduled in your system.
@endif

@if($appointmentData['role'] == 'doctor')
## Doctor Notification
You have a cancelled appointment.
@endif

@if($appointmentData['role'] == 'patient')
## Patient Notification
Your appointment has been successfully cancelled. Still you can book another appointment.
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent