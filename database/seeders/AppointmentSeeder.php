<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $doctor = Doctor::first();
        $specialty = Specialty::where('name', 'Pediatra')->first();
        $paciente = User::where('email', 'paciente@ceciamb.test')->first();

        // Cita pendiente con bloqueo activo (no expirada)
        Appointment::updateOrCreate(
            [
                'patient_email' => 'paciente@ceciamb.test',
                'requested_date' => now()->addDays(3)->toDateString(),
            ],
            [
                'patient_name' => 'Paciente de Prueba',
                'patient_phone' => '04141234567',
                'user_id' => $paciente?->id,
                'doctor_id' => $doctor?->id,
                'specialty_id' => $specialty?->id,
                'requested_time' => '09:00:00',
                'message' => 'Consulta de rutina para mi hijo.',
                'status' => 'pendiente',
                'expires_at' => now()->addHours(24),
            ]
        );

        // Cita pendiente expirada (para probar el Cron Job)
        Appointment::updateOrCreate(
            [
                'patient_email' => 'paciente-no-activado@ceciamb.test',
                'requested_date' => now()->addDays(5)->toDateString(),
            ],
            [
                'patient_name' => 'Paciente No Activado',
                'patient_phone' => '04241234567',
                'user_id' => null,
                'doctor_id' => null,
                'specialty_id' => $specialty?->id,
                'requested_time' => '10:00:00',
                'message' => 'Solicitud sin médico específico.',
                'status' => 'pendiente',
                'expires_at' => now()->subHours(2),
            ]
        );

        // Cita confirmada
        Appointment::updateOrCreate(
            [
                'patient_email' => 'paciente-confirmado@ceciamb.test',
                'requested_date' => now()->addDays(2)->toDateString(),
            ],
            [
                'patient_name' => 'Paciente Confirmado',
                'patient_phone' => '04161234567',
                'user_id' => null,
                'doctor_id' => $doctor?->id,
                'specialty_id' => null,
                'requested_time' => '11:30:00',
                'message' => null,
                'status' => 'confirmada',
                'expires_at' => null,
            ]
        );
    }
}