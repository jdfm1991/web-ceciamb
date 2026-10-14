<?php

namespace Tests\Feature\Patient;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentOwnershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_paciente_no_puede_ver_cita_de_otro_paciente(): void
    {
        $pacienteA = User::factory()->create(['role' => 'paciente']);
        $pacienteB = User::factory()->create(['role' => 'paciente']);

        $citaDeB = Appointment::factory()->create([
            'user_id' => $pacienteB->id,
        ]);

        $response = $this->actingAs($pacienteA)->get("/mi-cuenta/citas/{$citaDeB->id}");

        $response->assertNotFound();
    }

    public function test_paciente_puede_ver_su_propia_cita(): void
    {
        $paciente = User::factory()->create(['role' => 'paciente']);

        $cita = Appointment::factory()->create([
            'user_id' => $paciente->id,
        ]);

        $response = $this->actingAs($paciente)->get("/mi-cuenta/citas/{$cita->id}");

        $response->assertSuccessful();
    }

    public function test_paciente_no_puede_cancelar_cita_de_otro(): void
    {
        $pacienteA = User::factory()->create(['role' => 'paciente']);
        $pacienteB = User::factory()->create(['role' => 'paciente']);

        $citaDeB = Appointment::factory()->create([
            'user_id' => $pacienteB->id,
            'status' => 'pendiente',
            'requested_date' => now()->addDays(5),
        ]);

        $response = $this->actingAs($pacienteA)->post("/mi-cuenta/citas/{$citaDeB->id}/cancelar");

        $response->assertNotFound();

        // La cita de B sigue pendiente
        $citaDeB->refresh();
        $this->assertEquals('pendiente', $citaDeB->status);
    }

    public function test_paciente_solo_ve_sus_propias_citas_en_el_listado(): void
    {
        $pacienteA = User::factory()->create(['role' => 'paciente']);
        $pacienteB = User::factory()->create(['role' => 'paciente']);

        Appointment::factory()->create([
            'user_id' => $pacienteA->id,
            'patient_name' => 'Paciente A',
        ]);

        Appointment::factory()->create([
            'user_id' => $pacienteB->id,
            'patient_name' => 'Paciente B',
        ]);

        $response = $this->actingAs($pacienteA)->get('/mi-cuenta/citas');

        $response->assertSuccessful();
        // Verificar que no aparecen citas de B
        $response->assertDontSee('Paciente B');
    }
}