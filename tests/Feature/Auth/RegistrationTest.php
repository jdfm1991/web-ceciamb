<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_paciente_puede_registrarse(): void
    {
        $response = $this->post('/registro', [
            'name' => 'Juan Test',
            'email' => 'juan@test.com',
            'phone' => '04141234567',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Diagnóstico: ver la respuesta completa
        dump('Status: ' . $response->status());
        dump('Session errors: ', session()->all()['errors'] ?? 'sin errores');
        dump('Usuarios en BD: ' . User::count());
        dump('Todos los usuarios:', User::all()->toArray());

        $response->assertRedirect();

        $user = User::where('email', 'juan@test.com')->first();

        $this->assertNotNull($user);
        $this->assertEquals('paciente', $user->role);
        $this->assertNotNull($user->activation_token);
        $this->assertNotNull($user->activation_expires_at);
        $this->assertNull($user->email_verified_at);
    }

    public function test_registro_falla_con_email_duplicado(): void
    {
        User::factory()->create(['email' => 'existente@test.com']);

        $response = $this->post('/registro', [
            'name' => 'Otro Test',
            'email' => 'existente@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertEquals(1, User::where('email', 'existente@test.com')->count());
    }

    public function test_registro_falla_con_password_debil(): void
    {
        $response = $this->post('/registro', [
            'name' => 'Test',
            'email' => 'test@test.com',
            'password' => '123',
            'password_confirmation' => '123',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertNull(User::where('email', 'test@test.com')->first());
    }

    public function test_registro_falla_sin_confirmacion_de_password(): void
    {
        $response = $this->post('/registro', [
            'name' => 'Test',
            'email' => 'test@test.com',
            'password' => 'password123',
            'password_confirmation' => 'diferente123',
        ]);

        $response->assertSessionHasErrors('password');
    }
}
