<?php

namespace Tests\Feature\Validation;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationFormValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    protected function validPayload(): array
    {
        return [
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'phone' => '04141234567',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];
    }

    // === Nombre ===

    public function test_nombre_es_requerido(): void
    {
        $response = $this->post('/registro', array_merge($this->validPayload(), [
            'name' => '',
        ]));

        $response->assertSessionHasErrors('name');
    }

    public function test_nombre_debe_tener_al_menos_3_caracteres(): void
    {
        $response = $this->post('/registro', array_merge($this->validPayload(), [
            'name' => 'AB',
        ]));

        $response->assertSessionHasErrors('name');
    }

    // === Email ===

    public function test_email_es_requerido(): void
    {
        $response = $this->post('/registro', array_merge($this->validPayload(), [
            'email' => '',
        ]));

        $response->assertSessionHasErrors('email');
    }

    public function test_email_debe_ser_valido(): void
    {
        $response = $this->post('/registro', array_merge($this->validPayload(), [
            'email' => 'no-es-un-email',
        ]));

        $response->assertSessionHasErrors('email');
    }

    public function test_email_debe_ser_unico(): void
    {
        User::factory()->create(['email' => 'existente@example.com']);

        $response = $this->post('/registro', array_merge($this->validPayload(), [
            'email' => 'existente@example.com',
        ]));

        $response->assertSessionHasErrors('email');
    }

    // === Teléfono (opcional) ===

    public function test_telefono_es_opcional(): void
    {
        $payload = $this->validPayload();
        unset($payload['phone']);

        $response = $this->post('/registro', $payload);

        $response->assertSessionDoesntHaveErrors('phone');
    }

    public function test_telefono_rechaza_formato_invalido(): void
    {
        $response = $this->post('/registro', array_merge($this->validPayload(), [
            'phone' => 'abc#$',
        ]));

        $response->assertSessionHasErrors('phone');
    }

    // === Password ===

    public function test_password_es_requerido(): void
    {
        $response = $this->post('/registro', array_merge($this->validPayload(), [
            'password' => '',
            'password_confirmation' => '',
        ]));

        $response->assertSessionHasErrors('password');
    }

    public function test_password_debe_tener_al_menos_8_caracteres(): void
    {
        $response = $this->post('/registro', array_merge($this->validPayload(), [
            'password' => 'short1',
            'password_confirmation' => 'short1',
        ]));

        $response->assertSessionHasErrors('password');
    }

    public function test_password_debe_incluir_al_menos_una_letra(): void
    {
        $response = $this->post('/registro', array_merge($this->validPayload(), [
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]));

        $response->assertSessionHasErrors('password');
    }

    public function test_password_debe_incluir_al_menos_un_numero(): void
    {
        $response = $this->post('/registro', array_merge($this->validPayload(), [
            'password' => 'sololetras',
            'password_confirmation' => 'sololetras',
        ]));

        $response->assertSessionHasErrors('password');
    }

    public function test_password_confirmation_debe_coincidir(): void
    {
        $response = $this->post('/registro', array_merge($this->validPayload(), [
            'password' => 'password123',
            'password_confirmation' => 'diferente123',
        ]));

        $response->assertSessionHasErrors('password');
    }

    // === Flujo exitoso ===

    public function test_formulario_valido_es_aceptado(): void
    {
        $response = $this->post('/registro', $this->validPayload());

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', [
            'email' => 'juan@example.com',
            'role' => 'paciente',
        ]);
    }
}