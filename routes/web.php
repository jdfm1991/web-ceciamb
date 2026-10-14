<?php

use App\Http\Controllers\Public\AppointmentController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\DoctorController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ServiceController;
use App\Http\Controllers\Public\SpecialtyController;
use App\Http\Controllers\Auth\PatientAuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/* Route::get('/', function () {
    return view('welcome');
}); */

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'message' => 'El portal digital está en construcción.',
    ]);
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/especialidades', [SpecialtyController::class, 'index'])->name('specialties.index');
Route::get('/medicos', [DoctorController::class, 'index'])->name('doctors.index');
Route::get('/medicos/{doctor:slug}', [DoctorController::class, 'show'])->name('doctors.show');
Route::get('/servicios', [ServiceController::class, 'index'])->name('services.index');
Route::get('/contacto', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contacto', [ContactController::class, 'store'])->name('contact.store');
Route::get('/citas', [AppointmentController::class, 'create'])->name('appointments.create');
Route::post('/citas', [AppointmentController::class, 'store'])->name('appointments.store');
Route::get('/citas/{appointment}/exito', [AppointmentController::class, 'success'])->name('appointments.success');

// Autenticación de pacientes
Route::middleware('guest')->group(function () {
    Route::get('/registro', [PatientAuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [PatientAuthController::class, 'register']);

    Route::get('/login', [PatientAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [PatientAuthController::class, 'login']);

    Route::get('/reenviar-activacion', [PatientAuthController::class, 'showResend'])->name('activation.resend');
    Route::post('/reenviar-activacion', [PatientAuthController::class, 'resend']);
});

// Activación (sin middleware guest, porque el usuario puede estar logueado)
Route::get('/activar-cuenta/{token}', [PatientAuthController::class, 'activate'])->name('activation.activate');
Route::get('/activacion-pendiente', [PatientAuthController::class, 'pending'])->name('activation.pending');

// Logout (requiere auth)
Route::post('/logout', [PatientAuthController::class, 'logout'])->middleware('auth')->name('logout');
