<?php

use App\Livewire\Patient\Appointments\MyAppointments;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;
use App\Livewire\Patient\Booking\BookingComponent;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});
Route::get('/all/doctors', \App\Livewire\AllDoctors::class)->name('doctors');
Route::get('booking/page/{id}', BookingComponent::class);

Route::get('/my-appointments',MyAppointments::class)->name('appointments');

Route::get('/reschedule/{appointment_id}', \App\Livewire\Patient\Appointments\RescheduleForm::class)->name('reschedule');

Route::get('/article/{id}', \App\Livewire\ArticlePage::class)->name('article.page');
require __DIR__.'/auth.php';
