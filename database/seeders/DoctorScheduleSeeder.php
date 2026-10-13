<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DoctorScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // Horario típico: Lunes a Viernes de 8:00 a 12:00 y de 14:00 a 18:00
        $weekdays = [
            Carbon::MONDAY,
            Carbon::TUESDAY,
            Carbon::WEDNESDAY,
            Carbon::THURSDAY,
            Carbon::FRIDAY,
        ];

        $doctors = Doctor::all();

        foreach ($doctors as $doctor) {
            foreach ($weekdays as $day) {
                // Bloque matutino
                DoctorSchedule::updateOrCreate(
                    [
                        'doctor_id' => $doctor->id,
                        'day_of_week' => $day,
                        'start_time' => '08:00:00',
                    ],
                    [
                        'end_time' => '12:00:00',
                        'slot_duration_minutes' => 30,
                        'is_active' => true,
                    ]
                );

                // Bloque vespertino
                DoctorSchedule::updateOrCreate(
                    [
                        'doctor_id' => $doctor->id,
                        'day_of_week' => $day,
                        'start_time' => '14:00:00',
                    ],
                    [
                        'end_time' => '18:00:00',
                        'slot_duration_minutes' => 30,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}