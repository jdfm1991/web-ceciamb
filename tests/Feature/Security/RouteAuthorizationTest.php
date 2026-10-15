<?php

namespace Tests\Feature\Security;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouteAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_rutas_publicas_son_accesibles_sin_autenticacion(): void
    {
        $rutasPublicas = [
            '/',
            '/especialidades',
            '/medicos',
            '/servicios',
            '/sobre-nosotros',
            '/contacto',
            '/citas',
            '/login',
            '/registro',
        ];

        foreach ($rutasPublicas as $ruta) {
            $response = $this->get($ruta);
            $response->assertSuccessful();
        }
    }

    public function test_rutas_admin_requieren_autenticacion(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/admin/login');
    }

    public function test_rutas_mi_cuenta_requieren_autenticacion(): void
    {
        $rutas = [
            '/mi-cuenta',
            '/mi-cuenta/citas',
            '/mi-cuenta/perfil',
        ];

        foreach ($rutas as $ruta) {
            $response = $this->get($ruta);
            $response->assertRedirect('/login');
        }
    }

    public function test_rutas_api_son_accesibles_sin_autenticacion(): void
    {
        // El endpoint de franjas disponibles es público (lo usa el wizard sin auth)
        $response = $this->getJson('/api/doctors/1/available-slots?date=2026-11-09');
        
        // Puede devolver 404 (doctor no existe) o 422 (validación),
        // pero NO debe devolver 401/403
        $this->assertNotContains($response->status(), [401, 403]);
    }

    public function test_paciente_no_puede_acceder_a_rutas_admin(): void
    {
        $paciente = User::factory()->create(['role' => 'paciente']);

        $response = $this->actingAs($paciente)->get('/admin');

        $this->assertContains($response->status(), [302, 403]);
    }

    public function test_admin_no_puede_acceder_a_mi_cuenta(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/mi-cuenta');

        $response->assertForbidden();
    }
}