<?php

use App\Http\Controllers\Api\AppointmentSlotController;
use App\Http\Controllers\Api\SpecialtyController;
use Illuminate\Support\Facades\Route;

Route::get('/doctors/{doctor}/available-slots', [AppointmentSlotController::class, 'index'])
    ->name('api.doctors.available-slots');


Route::get('/specialties/{specialty}/doctors', [SpecialtyController::class, 'doctors'])
    ->name('api.specialties.doctors');
