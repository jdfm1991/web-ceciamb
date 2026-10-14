<?php

use App\Http\Controllers\Public\DoctorController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ServiceController;
use App\Http\Controllers\Public\SpecialtyController;
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