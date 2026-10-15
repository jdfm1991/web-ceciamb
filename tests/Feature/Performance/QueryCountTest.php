<?php

namespace Tests\Feature\Performance;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Specialty;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class QueryCountTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Ejecuta una URL y cuenta las consultas realizadas.
     */
    protected function countQueries(string $url): int
    {
        $count = 0;
        DB::listen(function () use (&$count) {
            $count++;
        });

        $this->get($url);

        return $count;
    }

    public function test_home_tiene_pocas_consultas(): void
    {
        // Crear datos mínimos
        Specialty::factory()->count(5)->create(['is_active' => true]);
        Doctor::factory()->count(3)->create(['is_active' => true]);

        $count = $this->countQueries('/');

        // Objetivo: menos de 15 consultas
        // (incluye clinic_info cached, servicios, especialidades, médicos, etc.)
        $this->assertLessThan(
            15,
            $count,
            "La home ejecuta demasiadas consultas: {$count}"
        );
    }

    public function test_especialidades_tiene_pocas_consultas(): void
    {
        Specialty::factory()->count(10)->create(['is_active' => true]);

        $count = $this->countQueries('/especialidades');

        // Objetivo: menos de 10 consultas
        $this->assertLessThan(
            10,
            $count,
            "Especialidades ejecuta demasiadas consultas: {$count}"
        );
    }

    public function test_medicos_tiene_pocas_consultas(): void
    {
        $specialty = Specialty::factory()->create(['is_active' => true]);
        $doctors = Doctor::factory()->count(5)->create(['is_active' => true]);

        foreach ($doctors as $doctor) {
            $doctor->specialties()->attach($specialty->id);
        }

        $count = $this->countQueries('/medicos');

        // Objetivo: menos de 10 consultas
        $this->assertLessThan(
            10,
            $count,
            "Médicos ejecuta demasiadas consultas: {$count}"
        );
    }

    public function test_perfil_de_medico_tiene_pocas_consultas(): void
    {
        $specialty = Specialty::factory()->create(['is_active' => true]);
        $doctor = Doctor::factory()->create(['is_active' => true]);
        $doctor->specialties()->attach($specialty->id);

        DoctorSchedule::create([
            'doctor_id' => $doctor->id,
            'day_of_week' => 1,
            'start_time' => '08:00:00',
            'end_time' => '12:00:00',
            'slot_duration_minutes' => 30,
            'is_active' => true,
        ]);

        $count = $this->countQueries("/medicos/{$doctor->slug}");

        // Objetivo: menos de 10 consultas
        $this->assertLessThan(
            10,
            $count,
            "Perfil de médico ejecuta demasiadas consultas: {$count}"
        );
    }

    public function test_servicios_tiene_pocas_consultas(): void
    {
        \App\Models\Service::factory()->count(6)->create(['is_active' => true]);

        $count = $this->countQueries('/servicios');

        $this->assertLessThan(
            10,
            $count,
            "Servicios ejecuta demasiadas consultas: {$count}"
        );
    }
}