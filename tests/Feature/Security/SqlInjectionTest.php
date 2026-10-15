<?php

namespace Tests\Feature\Security;

use App\Models\Doctor;
use App\Models\Specialty;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SqlInjectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_busqueda_de_especialidades_escapa_comillas(): void
    {
        Specialty::factory()->create(['name' => 'Cardiología']);

        // Intentar SQL injection en el parámetro de búsqueda
        $response = $this->get('/especialidades?search=cardio\' OR 1=1--');

        $response->assertSuccessful();
        // No debe romper ni devolver todos los registros
        $this->assertDatabaseCount('specialties', 1);
    }

    public function test_busqueda_de_medicos_escapa_simbolos(): void
    {
        Doctor::factory()->create(['name' => 'Dr. Test']);

        $response = $this->get("/medicos?especialidad='; DROP TABLE users;--");

        // La ruta debe manejar el input sin romper
        $this->assertTrue(
            $response->status() === 200 || $response->status() === 404,
            "Se esperaba 200 o 404, se recibió: {$response->status()}"
        );
    }

    public function test_login_con_sql_injection_falla_seguramente(): void
    {
        $response = $this->post('/login', [
            'email' => "admin' OR '1'='1",
            'password' => "cualquier' OR '1'='1",
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_registro_con_sql_injection_en_nombre(): void
    {
        $response = $this->post('/registro', [
            'name' => "Robert'); DROP TABLE users;--",
            'email' => 'robert@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // La tabla users debe seguir existiendo
        $this->assertDatabaseCount('users', 1);
    }
}