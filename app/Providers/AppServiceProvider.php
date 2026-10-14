<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\DoctorSchedule;
use App\Observers\AppointmentObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\DoctorScheduleObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Appointment::observe(AppointmentObserver::class);
        DoctorSchedule::observe(DoctorScheduleObserver::class);
    }
}
