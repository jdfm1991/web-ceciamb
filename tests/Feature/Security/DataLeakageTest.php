<?php

namespace Tests\Feature\Security;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DataLeakageTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_no_expone_campos_sensibles_en_json(): void
    {
        $user = User::factory()->create([
            'activation_token' => 'token-secreto-123',
        ]);

        $array = $user->toArray();

        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
        $this->assertArrayNotHasKey('activation_token', $array);
    }

    public function test_paciente_no_ve_datos_de_otros_pacientes(): void
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
        $response->assertDontSee('Paciente B');
    }

    public function test_paciente_no_puede_acceder_a_cita_ajena(): void
    {
        $pacienteA = User::factory()->create(['role' => 'paciente']);
        $pacienteB = User::factory()->create(['role' => 'paciente']);

        $citaB = Appointment::factory()->create(['user_id' => $pacienteB->id]);

        $response = $this->actingAs($pacienteA)->get("/mi-cuenta/citas/{$citaB->id}");

        $response->assertNotFound();
    }

    public function test_password_hash_no_aparece_en_respuestas(): void
    {
        $user = User::factory()->create();
        $hash = $user->password;

        // Simular una respuesta donde se devuelve el usuario
        $response = $user->toArray();

        $this->assertArrayNotHasKey('password', $response);
        $this->assertArrayNotHasKey('remember_token', $response);
    }
}