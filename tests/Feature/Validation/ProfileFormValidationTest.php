<?php

namespace Tests\Feature\Validation;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileFormValidationTest extends TestCase
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
        ]);
    }

    // === Nombre ===

    public function test_nombre_es_requerido(): void
    {
        $response = $this->actingAs($this->paciente)->post('/mi-cuenta/perfil', [
            'name' => '',
            'phone' => '04141234567',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_nombre_debe_tener_al_menos_3_caracteres(): void
    {
        $response = $this->actingAs($this->paciente)->post('/mi-cuenta/perfil', [
            'name' => 'AB',
            'phone' => '04141234567',
        ]);

        $response->assertSessionHasErrors('name');
    }

    // === Teléfono (opcional) ===

    public function test_telefono_es_opcional(): void
    {
        $response = $this->actingAs($this->paciente)->post('/mi-cuenta/perfil', [
            'name' => 'Juan Pérez',
            'phone' => '',
        ]);

        $response->assertSessionDoesntHaveErrors('phone');
    }

    public function test_telefono_rechaza_formato_invalido(): void
    {
        $response = $this->actingAs($this->paciente)->post('/mi-cuenta/perfil', [
            'name' => 'Juan Pérez',
            'phone' => 'abc#$',
        ]);

        $response->assertSessionHasErrors('phone');
    }

    // === Flujo exitoso ===

    public function test_formulario_valido_actualiza_perfil(): void
    {
        $response = $this->actingAs($this->paciente)->post('/mi-cuenta/perfil', [
            'name' => 'Juan Actualizado',
            'phone' => '04161234567',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->paciente->refresh();
        $this->assertEquals('Juan Actualizado', $this->paciente->name);
        $this->assertEquals('04161234567', $this->paciente->phone);
    }
}