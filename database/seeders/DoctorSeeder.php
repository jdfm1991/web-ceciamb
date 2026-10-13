<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Specialty;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        // Médico de prueba 1: Cardiólogo con dos especialidades
        $doctor1 = Doctor::updateOrCreate(
            ['name' => 'Dr. Juan Pérez'],
            [
                'slug' => Str::slug('Dr. Juan Pérez'),
                'biography' => 'Médico cardiólogo con 15 años de experiencia en el diagnóstico y tratamiento de enfermedades cardiovasculares.',
                'is_active' => true,
            ]
        );

        $specialties1 = Specialty::whereIn('name', [
            'Internista',
            'Intensivista',
        ])->pluck('id');

        $doctor1->specialties()->sync($specialties1);

        // Médico de prueba 2: Pediatra con una especialidad
        $doctor2 = Doctor::updateOrCreate(
            ['name' => 'Dra. María González'],
            [
                'slug' => Str::slug('Dra. María González'),
                'biography' => 'Pediatra especializada en el cuidado integral de niños y adolescentes.',
                'is_active' => true,
            ]
        );

        $specialties2 = Specialty::whereIn('name', [
            'Pediatra',
        ])->pluck('id');

        $doctor2->specialties()->sync($specialties2);
    }
}