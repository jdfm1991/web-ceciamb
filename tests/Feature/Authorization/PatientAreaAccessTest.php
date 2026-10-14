<?php

namespace Tests\Feature\Authorization;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientAreaAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitante_anonimo_es_redirigido_al_login(): void
    {
        $response = $this->get('/mi-cuenta');

        $response->assertRedirect('/login');
    }

    public function test_paciente_puede_acceder_a_mi_cuenta(): void
    {
        $paciente = User::factory()->create([
            'role' => 'paciente',
            'activation_token' => null,
        ]);

        $response = $this->actingAs($paciente)->get('/mi-cuenta');

        $response->assertSuccessful();
    }

    public function test_admin_no_puede_acceder_a_mi_cuenta(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/mi-cuenta');

        $response->assertForbidden();
    }

    public function test_recepcionista_no_puede_acceder_a_mi_cuenta(): void
    {
        $recepcionista = User::factory()->create(['role' => 'recepcionista']);

        $response = $this->actingAs($recepcionista)->get('/mi-cuenta');

        $response->assertForbidden();
    }
}