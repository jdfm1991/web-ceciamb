<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    public function run(): void
    {
        $specialties = [
            // Especialidades médicas
            ['name' => 'Fisioterapista', 'category' => 'profesion_salud'],
            ['name' => 'Gastroenterólogo', 'category' => 'medica'],
            ['name' => 'Gastroenterólogo Infantil', 'category' => 'subespecialidad'],
            ['name' => 'Geriatra', 'category' => 'medica'],
            ['name' => 'Geriatra Terapia Intensiva', 'category' => 'subespecialidad'],
            ['name' => 'Ginecólogo', 'category' => 'medica'],
            ['name' => 'Ginecólogo Infanto-Juvenil', 'category' => 'subespecialidad'],
            ['name' => 'Ginecólogo Materno Fetal Perinatólogo', 'category' => 'subespecialidad'],
            ['name' => 'Ginecólogo-Obstetra', 'category' => 'medica'],
            ['name' => 'Hematólogo', 'category' => 'medica'],
            ['name' => 'Hemoterapista', 'category' => 'subespecialidad'],
            ['name' => 'Imagenólogo', 'category' => 'medica'],
            ['name' => 'Infectólogo', 'category' => 'medica'],
            ['name' => 'Infectólogo Pediatra', 'category' => 'subespecialidad'],
            ['name' => 'Intensivista', 'category' => 'medica'],
            ['name' => 'Internista', 'category' => 'medica'],
            ['name' => 'Internista / Oncólogo', 'category' => 'subespecialidad'],
            ['name' => 'Internista Gastroenterólogo', 'category' => 'subespecialidad'],
            ['name' => 'Mastólogo', 'category' => 'subespecialidad'],
            ['name' => 'Medicina Marítima', 'category' => 'medica'],
            ['name' => 'Médico Cirujano', 'category' => 'medica'],
            ['name' => 'Médico Foniatra', 'category' => 'subespecialidad'],
            ['name' => 'Médico Ocupacional', 'category' => 'medica'],
            ['name' => 'Nefrólogo', 'category' => 'medica'],
            ['name' => 'Neonatólogo', 'category' => 'subespecialidad'],
            ['name' => 'Neonatólogo Pediatra', 'category' => 'subespecialidad'],
            ['name' => 'Neumonólogo', 'category' => 'medica'],
            ['name' => 'Neumonólogo-Pediatra', 'category' => 'subespecialidad'],
            ['name' => 'Neurocirugía Infantil', 'category' => 'subespecialidad'],
            ['name' => 'Neurocirujano', 'category' => 'medica'],
            ['name' => 'Neurólogo', 'category' => 'medica'],
            ['name' => 'Neuro-Pediatra', 'category' => 'subespecialidad'],
            ['name' => 'Nutricionista', 'category' => 'profesion_salud'],
            ['name' => 'Obstetra', 'category' => 'medica'],
            ['name' => 'Odontólogo', 'category' => 'profesion_salud'],
            ['name' => 'Oftalmólogo', 'category' => 'medica'],
            ['name' => 'Oncólogo', 'category' => 'medica'],
            ['name' => 'Otorrinolaringólogo', 'category' => 'medica'],
            ['name' => 'Patólogo', 'category' => 'medica'],
            ['name' => 'Pediatra', 'category' => 'medica'],
            ['name' => 'Pediatra Cardiólogo Infantil', 'category' => 'subespecialidad'],
            ['name' => 'Pediatra-Neonatólogo', 'category' => 'subespecialidad'],
            ['name' => 'Pediatra-Puericultor', 'category' => 'subespecialidad'],
            ['name' => 'Pediatra-Toxicólogo', 'category' => 'subespecialidad'],
            ['name' => 'Psicología', 'category' => 'profesion_salud'],
            ['name' => 'Psiquiatra', 'category' => 'medica'],
            ['name' => 'Radiólogo', 'category' => 'medica'],
            ['name' => 'Reumatólogo', 'category' => 'medica'],
            ['name' => 'Técnico Radiólogo', 'category' => 'tecnica'],
            ['name' => 'Terapista del Dolor', 'category' => 'subespecialidad'],
            ['name' => 'Terapista Respiratoria', 'category' => 'tecnica'],
            ['name' => 'Traumatólogo', 'category' => 'medica'],
            ['name' => 'Traumatólogo-Ortopedista', 'category' => 'subespecialidad'],
            ['name' => 'Urólogo', 'category' => 'medica'],
            ['name' => 'Urólogo Pediátrico', 'category' => 'subespecialidad'],
        ];

        foreach ($specialties as $specialty) {
            Specialty::updateOrCreate(
                ['name' => $specialty['name']],
                [
                    'slug' => \Illuminate\Support\Str::slug($specialty['name']),
                    'category' => $specialty['category'],
                    'is_active' => true,
                ]
            );
        }
    }
}