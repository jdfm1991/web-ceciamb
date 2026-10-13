<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Banco de Sangre',
                'description' => 'Servicio de recolección, procesamiento y suministro de sangre y sus componentes para transfusiones y procedimientos médicos.',
            ],
            [
                'name' => 'Servicio de Imágenes',
                'description' => 'Estudios de diagnóstico por imágenes que incluyen rayos X, resonancias magnéticas y otros procedimientos de alta precisión.',
            ],
            [
                'name' => 'Laboratorio Clínico',
                'description' => 'Análisis clínicos de laboratorio para apoyo diagnóstico, seguimiento de tratamientos y evaluaciones preventivas.',
            ],
            [
                'name' => 'Fisiatría (Medicina Física y Rehabilitación)',
                'description' => 'Tratamientos de rehabilitación física y terapia para la recuperación funcional de pacientes.',
            ],
            [
                'name' => 'Unidad de Cuidados Intensivos',
                'description' => 'Atención médica especializada para pacientes en estado crítico, con monitoreo continuo y equipamiento de alta complejidad.',
            ],
            [
                'name' => 'Nutrición y Dietética',
                'description' => 'Evaluación nutricional y planes alimentarios personalizados para pacientes con requerimientos específicos.',
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['name' => $service['name']],
                [
                    'slug' => Str::slug($service['name']),
                    'description' => $service['description'],
                    'is_active' => true,
                ]
            );
        }
    }
}