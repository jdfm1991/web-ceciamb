<?php

namespace Tests\Feature\Validation;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordChangeValidationTest extends TestCase
{
    use RefreshDatabase;

    protected User $paciente;

    protected function setUp(): void
    {
        parent::setUp();

        $this->paciente = User::factory()->create([
            'role' => 'paciente',
            'activation_token' => null,
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
        ]);
    }

    // === Contraseña actual ===

    public function test_current_password_es_requerido(): void
    {
        $response = $this->actingAs($this->paciente)->post('/mi-cuenta/perfil/password', [
            'current_password' => '',
            'password' => 'nuevopass123',
            'password_confirmation' => 'nuevopass123',
        ]);

        $response->assertSessionHasErrors('current_password');
    }

    public function test_current_password_debe_ser_correcto(): void
    {
        $response = $this->actingAs($this->paciente)->post('/mi-cuenta/perfil/password', [
            'current_password' => 'incorrecta',
            'password' => 'nuevopass123',
            'password_confirmation' => 'nuevopass123',
        ]);

        $response->assertSessionHasErrors('current_password');
    }

    // === Nueva contraseña ===

    public function test_nueva_password_es_requerida(): void
    {
        $response = $this->actingAs($this->paciente)->post('/mi-cuenta/perfil/password', [
            'current_password' => 'password123',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_nueva_password_debe_tener_al_menos_8_caracteres(): void
    {
        $response = $this->actingAs($this->paciente)->post('/mi-cuenta/perfil/password', [
            'current_password' => 'password123',
            'password' => 'short1',
            'password_confirmation' => 'short1',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_nueva_password_debe_incluir_letra_y_numero(): void
    {
        $response = $this->actingAs($this->paciente)->post('/mi-cuenta/perfil/password', [
            'current_password' => 'password123',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_nueva_password_confirmation_debe_coincidir(): void
    {
        $response = $this->actingAs($this->paciente)->post('/mi-cuenta/perfil/password', [
            'current_password' => 'password123',
            'password' => 'nuevopass123',
            'password_confirmation' => 'diferente123',
        ]);

        $response->assertSessionHasErrors('password');
    }

    // === Flujo exitoso ===

    public function test_cambio_de_password_valido(): void
    {
        $response = $this->actingAs($this->paciente)->post('/mi-cuenta/perfil/password', [
            'current_password' => 'password123',
            'password' => 'nuevopass123',
            'password_confirmation' => 'nuevopass123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->paciente->refresh();
        $this->assertTrue(Hash::check('nuevopass123', $this->paciente->password));
    }
}