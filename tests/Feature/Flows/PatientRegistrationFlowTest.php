<?php

namespace Tests\Feature\Flows;

use App\Mail\PatientActivationMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PatientRegistrationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_flujo_completo_de_registro_y_activacion(): void
    {
        Mail::fake();

        // === 1. Paciente se registra ===
        $response = $this->post('/registro', [
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'phone' => '04141234567',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect();

        // Verificar que el usuario se creó
        $user = User::where('email', 'juan@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('paciente', $user->role);
        $this->assertNotNull($user->activation_token);
        $this->assertNull($user->email_verified_at);

        // Verificar que se envió el correo
        Mail::assertSent(PatientActivationMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });

        // === 2. Extraer el token del correo ===
        $token = $user->activation_token;

        // === 3. Paciente activa su cuenta ===
        $response = $this->get("/activar-cuenta/{$token}");

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertNull($user->activation_token);
        $this->assertNotNull($user->email_verified_at);

        // === 4. Paciente inicia sesión ===
        $response = $this->post('/login', [
            'email' => 'juan@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/mi-cuenta');
        $this->assertAuthenticatedAs($user);

        // === 5. Paciente accede a su cuenta ===
        $response = $this->get('/mi-cuenta');
        $response->assertSuccessful();
    }

    public function test_flujo_de_registro_bloquea_login_sin_activar(): void
    {
        Mail::fake();

        // Registro
        $this->post('/registro', [
            'name' => 'Sin Activar',
            'email' => 'sinactivar@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Intento de login sin activar
        $response = $this->post('/login', [
            'email' => 'sinactivar@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_flujo_de_reenvio_de_activacion(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'reenvio@example.com',
            'role' => 'paciente',
            'activation_token' => 'token-viejo',
            'activation_expires_at' => now()->subHour(),
            'email_verified_at' => null,
        ]);

        $oldToken = $user->activation_token;

        // Reenviar activación
        $response = $this->post('/reenviar-activacion', [
            'email' => 'reenvio@example.com',
        ]);

        $response->assertRedirect();

        $user->refresh();
        $this->assertNotEquals($oldToken, $user->activation_token);
        $this->assertTrue($user->activation_expires_at->isFuture());

        Mail::assertSent(PatientActivationMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }
}