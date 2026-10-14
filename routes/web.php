<?php

use App\Http\Controllers\Public\HomeController;
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