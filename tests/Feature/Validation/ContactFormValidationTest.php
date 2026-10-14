<?php

namespace Tests\Feature\Validation;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    // === Nombre ===

    public function test_nombre_es_requerido(): void
    {
        $response = $this->post('/contacto', [
            'name' => '',
            'email' => 'test@example.com',
            'subject' => 'Consulta',
            'message' => 'Este es un mensaje válido de prueba.',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_nombre_debe_tener_al_menos_3_caracteres(): void
    {
        $response = $this->post('/contacto', [
            'name' => 'AB',
            'email' => 'test@example.com',
            'subject' => 'Consulta',
            'message' => 'Este es un mensaje válido de prueba.',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_nombre_no_puede_exceder_100_caracteres(): void
    {
        $response = $this->post('/contacto', [
            'name' => str_repeat('A', 101),
            'email' => 'test@example.com',
            'subject' => 'Consulta',
            'message' => 'Este es un mensaje válido de prueba.',
        ]);

        $response->assertSessionHasErrors('name');
    }

    // === Email ===

    public function test_email_es_requerido(): void
    {
        $response = $this->post('/contacto', [
            'name' => 'Juan Test',
            'email' => '',
            'subject' => 'Consulta',
            'message' => 'Este es un mensaje válido de prueba.',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_email_debe_ser_valido(): void
    {
        $response = $this->post('/contacto', [
            'name' => 'Juan Test',
            'email' => 'no-es-un-email',
            'subject' => 'Consulta',
            'message' => 'Este es un mensaje válido de prueba.',
        ]);

        $response->assertSessionHasErrors('email');
    }

    // === Teléfono (opcional) ===

    public function test_telefono_es_opcional(): void
    {
        $response = $this->post('/contacto', [
            'name' => 'Juan Test',
            'email' => 'test@example.com',
            'subject' => 'Consulta',
            'message' => 'Este es un mensaje válido de prueba.',
        ]);

        $response->assertSessionDoesntHaveErrors('phone');
    }

    public function test_telefono_rechaza_caracteres_invalidos(): void
    {
        $response = $this->post('/contacto', [
            'name' => 'Juan Test',
            'email' => 'test@example.com',
            'phone' => 'abc#$%',
            'subject' => 'Consulta',
            'message' => 'Este es un mensaje válido de prueba.',
        ]);

        $response->assertSessionHasErrors('phone');
    }

    public function test_telefono_acepta_formatos_validos(): void
    {
        $formatosValidos = [
            '04141234567',
            '0414-1234567',
            '+58 414 1234567',
            '(0286) 7120100',
        ];

        foreach ($formatosValidos as $telefono) {
            $response = $this->post('/contacto', [
                'name' => 'Juan Test',
                'email' => 'test@example.com',
                'phone' => $telefono,
                'subject' => 'Consulta',
                'message' => 'Este es un mensaje válido de prueba.',
            ]);

            $response->assertSessionDoesntHaveErrors('phone');
        }
    }

    // === Asunto ===

    public function test_asunto_es_requerido(): void
    {
        $response = $this->post('/contacto', [
            'name' => 'Juan Test',
            'email' => 'test@example.com',
            'subject' => '',
            'message' => 'Este es un mensaje válido de prueba.',
        ]);

        $response->assertSessionHasErrors('subject');
    }

    public function test_asunto_debe_tener_al_menos_3_caracteres(): void
    {
        $response = $this->post('/contacto', [
            'name' => 'Juan Test',
            'email' => 'test@example.com',
            'subject' => 'AB',
            'message' => 'Este es un mensaje válido de prueba.',
        ]);

        $response->assertSessionHasErrors('subject');
    }

    // === Mensaje ===

    public function test_mensaje_es_requerido(): void
    {
        $response = $this->post('/contacto', [
            'name' => 'Juan Test',
            'email' => 'test@example.com',
            'subject' => 'Consulta',
            'message' => '',
        ]);

        $response->assertSessionHasErrors('message');
    }

    public function test_mensaje_debe_tener_al_menos_10_caracteres(): void
    {
        $response = $this->post('/contacto', [
            'name' => 'Juan Test',
            'email' => 'test@example.com',
            'subject' => 'Consulta',
            'message' => 'Corto',
        ]);

        $response->assertSessionHasErrors('message');
    }

    public function test_mensaje_no_puede_exceder_2000_caracteres(): void
    {
        $response = $this->post('/contacto', [
            'name' => 'Juan Test',
            'email' => 'test@example.com',
            'subject' => 'Consulta',
            'message' => str_repeat('A', 2001),
        ]);

        $response->assertSessionHasErrors('message');
    }

    // === Flujo exitoso ===

    public function test_formulario_valido_es_aceptado(): void
    {
        $response = $this->post('/contacto', [
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'phone' => '0414-1234567',
            'subject' => 'Consulta sobre servicios',
            'message' => 'Este es un mensaje de prueba con suficiente longitud.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }
}