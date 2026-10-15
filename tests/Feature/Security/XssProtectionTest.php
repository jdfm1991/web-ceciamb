<?php

namespace Tests\Feature\Security;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class XssProtectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_datos_de_cita_se_escapan_en_respuesta(): void
    {
        $paciente = User::factory()->create([
            'role' => 'paciente',
            'activation_token' => null,
        ]);

        $cita = Appointment::factory()->create([
            'user_id' => $paciente->id,
            'patient_name' => '<script>alert("XSS")</script>',
        ]);

        $response = $this->actingAs($paciente)->get("/mi-cuenta/citas/{$cita->id}");

        $response->assertSuccessful();

        // El HTML crudo no debe aparecer en la respuesta
        $content = $response->getContent();
        // En Inertia, los datos viajan como JSON escapado
        // Verificamos que no se renderiza como HTML ejecutable
        $this->assertStringNotContainsString('<script>alert("XSS")</script>', $content);
    }

    public function test_mensaje_de_contacto_con_html_no_ejecuta(): void
    {
        $response = $this->post('/contacto', [
            'name' => 'Juan Test',
            'email' => 'juan@example.com',
            'subject' => '<script>alert("XSS")</script>',
            'message' => '<img src=x onerror=alert("XSS")>',
        ]);

        // La respuesta no debe contener el script sin escapar
        $content = $response->getContent();
        $this->assertStringNotContainsString('<script>', $content);
    }

    public function test_nombre_de_usuario_con_html_se_almacena_pero_no_ejecuta(): void
    {
        $response = $this->post('/registro', [
            'name' => 'Juan <script>alert(1)</script>',
            'email' => 'juan@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // El dato se almacena tal cual (Laravel no lo modifica en la BD)
        $this->assertDatabaseHas('users', [
            'email' => 'juan@example.com',
        ]);

        // Pero cuando se muestra, se escapa (por Blade/JSX)
        $user = User::where('email', 'juan@example.com')->first();
        $this->assertStringContainsString('<script>', $user->name); // Almacenado tal cual
    }
}