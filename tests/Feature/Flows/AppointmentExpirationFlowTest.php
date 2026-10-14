<?php

namespace Tests\Feature\Flows;

use App\Mail\AppointmentCancelledMail;
use App\Models\Appointment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AppointmentExpirationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_libera_citas_expiradas_sin_notificar(): void
    {
        Mail::fake();

        // Cita expirada (hace 2 horas)
        $expirada = Appointment::factory()->create([
            'status' => 'pendiente',
            'expires_at' => now()->subHours(2),
        ]);

        // Cita pendiente no expirada
        $vigente = Appointment::factory()->create([
            'status' => 'pendiente',
            'expires_at' => now()->addHours(10),
        ]);

        // Cita confirmada (no debe afectarse)
        $confirmada = Appointment::factory()->confirmed()->create();

        // Ejecutar el Job
        $this->artisan('appointments:release-expired')->assertSuccessful();

        // La expirada cambió a cancelada
        $expirada->refresh();
        $this->assertEquals('cancelada', $expirada->status);
        $this->assertNull($expirada->expires_at);

        // La vigente sigue pendiente
        $vigente->refresh();
        $this->assertEquals('pendiente', $vigente->status);

        // La confirmada no se tocó
        $confirmada->refresh();
        $this->assertEquals('confirmada', $confirmada->status);

        // IMPORTANTE: No se envió correo al paciente por la expiración
        Mail::assertNotSent(AppointmentCancelledMail::class);
    }

    public function test_job_no_hace_nada_si_no_hay_citas_expiradas(): void
    {
        Mail::fake();

        Appointment::factory()->create([
            'status' => 'pendiente',
            'expires_at' => now()->addHours(10),
        ]);

        $this->artisan('appointments:release-expired')->assertSuccessful();

        // No hay cambios
        $this->assertEquals(1, Appointment::where('status', 'pendiente')->count());
    }
}