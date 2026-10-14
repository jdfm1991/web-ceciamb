<?php

namespace Tests\Feature\Authorization;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitante_anonimo_no_puede_acceder_al_admin(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/admin/login');
    }

    public function test_admin_puede_acceder_al_panel(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertSuccessful();
    }

    public function test_recepcionista_puede_acceder_al_panel(): void
    {
        $recepcionista = User::factory()->create(['role' => 'recepcionista']);

        $response = $this->actingAs($recepcionista)->get('/admin');

        $response->assertSuccessful();
    }

    public function test_paciente_no_puede_acceder_al_panel(): void
    {
        $paciente = User::factory()->create(['role' => 'paciente']);

        $response = $this->actingAs($paciente)->get('/admin');

        // Filament puede redirigir al login (302) o devolver 403, dependiendo de la versión
        // Cualquiera de los dos es correcto: el paciente NO puede acceder
        $this->assertTrue(
            $response->status() === 403 || $response->status() === 302,
            "Se esperaba 403 o 302, se recibió: {$response->status()}"
        );
    }
}
