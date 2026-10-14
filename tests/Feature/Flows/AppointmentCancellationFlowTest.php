<?php

namespace Tests\Feature\Flows;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentCancellationFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow('2026-11-02 08:00:00');
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow(null);
        parent::tearDown();
    }

    public function test_paciente_cancela_su_cita_y_franja_se_libera(): void
    {
        $paciente = User::factory()->create([
            'role' => 'paciente',
            'activation_token' => null,
        ]);

        $doctor = Doctor::factory()->create(['is_active' => true]);
        DoctorSchedule::create([
            'doctor_id' => $doctor->id,
            'day_of_week' => Carbon::MONDAY,
            'start_time' => '08:00:00',
            'end_time' => '12:00:00',
            'slot_duration_minutes' => 30,
            'is_active' => true,
        ]);

        $appointment = Appointment::factory()->create([
            'user_id' => $paciente->id,
            'doctor_id' => $doctor->id,
            'requested_date' => '2026-11-09', // Lunes futuro
            'requested_time' => '09:00:00',
            'status' => 'pendiente',
            'expires_at' => now()->addHours(24),
        ]);

        // Verificar que la franja está bloqueada
        $response = $this->getJson("/api/doctors/{$doctor->id}/available-slots?date=2026-11-09");
        $slots = $response->json('slots');
        $startTimes = array_column($slots, 'start_time');
        $this->assertNotContains('09:00', $startTimes);

        // Paciente cancela
        $response = $this->actingAs($paciente)
            ->post("/mi-cuenta/citas/{$appointment->id}/cancelar");

        $response->assertRedirect();

        $appointment->refresh();
        $this->assertEquals('cancelada', $appointment->status);
        $this->assertNull($appointment->expires_at);

        // Verificar que la franja se libera
        $response = $this->getJson("/api/doctors/{$doctor->id}/available-slots?date=2026-11-09");
        $slots = $response->json('slots');
        $startTimes = array_column($slots, 'start_time');
        $this->assertContains('09:00', $startTimes);
    }

    public function test_paciente_no_puede_cancelar_cita_confirmada(): void
    {
        $paciente = User::factory()->create([
            'role' => 'paciente',
            'activation_token' => null,
        ]);

        $appointment = Appointment::factory()->confirmed()->create([
            'user_id' => $paciente->id,
            'requested_date' => '2026-11-09',
        ]);

        $response = $this->actingAs($paciente)
            ->post("/mi-cuenta/citas/{$appointment->id}/cancelar");

        $response->assertSessionHas('error');

        $appointment->refresh();
        $this->assertEquals('confirmada', $appointment->status);
    }
}