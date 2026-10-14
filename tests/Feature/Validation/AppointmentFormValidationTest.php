<?php

namespace Tests\Feature\Validation;

use App\Models\Doctor;
use App\Models\Specialty;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AppointmentFormValidationTest extends TestCase
{
    use RefreshDatabase;

    protected Doctor $doctor;
    protected Specialty $specialty;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Carbon::setTestNow('2026-11-02 08:00:00'); // Lunes

        $this->specialty = Specialty::factory()->create(['is_active' => true]);
        $this->doctor = Doctor::factory()->create(['is_active' => true]);
        $this->doctor->specialties()->attach($this->specialty->id);

        // NUEVO: Crear horario del médico para los lunes (2026-11-09 es lunes)
        \App\Models\DoctorSchedule::create([
            'doctor_id' => $this->doctor->id,
            'day_of_week' => Carbon::MONDAY,
            'start_time' => '08:00:00',
            'end_time' => '12:00:00',
            'slot_duration_minutes' => 30,
            'is_active' => true,
        ]);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow(null);
        parent::tearDown();
    }

    protected function validPayload(): array
    {
        return [
            'patient_name' => 'María González',
            'patient_email' => 'maria@example.com',
            'patient_phone' => '04161234567',
            'doctor_id' => $this->doctor->id,
            'specialty_id' => $this->specialty->id,
            'requested_date' => '2026-11-09',
            'requested_time' => '09:00',
            'message' => 'Consulta de rutina',
        ];
    }

    // === Nombre ===

    public function test_nombre_es_requerido(): void
    {
        $response = $this->post('/citas', array_merge($this->validPayload(), [
            'patient_name' => '',
        ]));

        $response->assertSessionHasErrors('patient_name');
    }

    public function test_nombre_debe_tener_al_menos_3_caracteres(): void
    {
        $response = $this->post('/citas', array_merge($this->validPayload(), [
            'patient_name' => 'AB',
        ]));

        $response->assertSessionHasErrors('patient_name');
    }

    // === Email ===

    public function test_email_es_requerido(): void
    {
        $response = $this->post('/citas', array_merge($this->validPayload(), [
            'patient_email' => '',
        ]));

        $response->assertSessionHasErrors('patient_email');
    }

    public function test_email_debe_ser_valido(): void
    {
        $response = $this->post('/citas', array_merge($this->validPayload(), [
            'patient_email' => 'no-es-un-email',
        ]));

        $response->assertSessionHasErrors('patient_email');
    }

    // === Teléfono ===

    public function test_telefono_es_requerido(): void
    {
        $response = $this->post('/citas', array_merge($this->validPayload(), [
            'patient_phone' => '',
        ]));

        $response->assertSessionHasErrors('patient_phone');
    }

    public function test_telefono_rechaza_formato_invalido(): void
    {
        $response = $this->post('/citas', array_merge($this->validPayload(), [
            'patient_phone' => 'abc',
        ]));

        $response->assertSessionHasErrors('patient_phone');
    }

    // === Doctor y Specialty (opcionales) ===

    public function test_doctor_id_es_opcional(): void
    {
        $payload = $this->validPayload();
        unset($payload['doctor_id']);

        $response = $this->post('/citas', $payload);

        $response->assertSessionDoesntHaveErrors('doctor_id');
    }

    public function test_specialty_id_es_opcional(): void
    {
        $payload = $this->validPayload();
        unset($payload['specialty_id']);

        $response = $this->post('/citas', $payload);

        $response->assertSessionDoesntHaveErrors('specialty_id');
    }

    public function test_doctor_id_debe_existir(): void
    {
        $response = $this->post('/citas', array_merge($this->validPayload(), [
            'doctor_id' => 99999,
        ]));

        $response->assertSessionHasErrors('doctor_id');
    }

    public function test_specialty_id_debe_existir(): void
    {
        $response = $this->post('/citas', array_merge($this->validPayload(), [
            'specialty_id' => 99999,
        ]));

        $response->assertSessionHasErrors('specialty_id');
    }

    // === Fecha ===

    public function test_fecha_es_requerida(): void
    {
        $response = $this->post('/citas', array_merge($this->validPayload(), [
            'requested_date' => '',
        ]));

        $response->assertSessionHasErrors('requested_date');
    }

    public function test_fecha_no_puede_ser_en_el_pasado(): void
    {
        $response = $this->post('/citas', array_merge($this->validPayload(), [
            'requested_date' => '2026-10-15',
        ]));

        $response->assertSessionHasErrors('requested_date');
    }

    public function test_fecha_debe_ser_hoy_o_futura(): void
    {
        $response = $this->post('/citas', array_merge($this->validPayload(), [
            'requested_date' => '2026-11-02', // hoy
        ]));

        $response->assertSessionDoesntHaveErrors('requested_date');
    }

    // === Hora ===

    public function test_hora_es_requerida(): void
    {
        $response = $this->post('/citas', array_merge($this->validPayload(), [
            'requested_time' => '',
        ]));

        $response->assertSessionHasErrors('requested_time');
    }

    public function test_hora_debe_tener_formato_hh_mm(): void
    {
        $response = $this->post('/citas', array_merge($this->validPayload(), [
            'requested_time' => '25:99',
        ]));

        $response->assertSessionHasErrors('requested_time');
    }

    public function test_hora_con_segundos_es_rechazada(): void
    {
        $response = $this->post('/citas', array_merge($this->validPayload(), [
            'requested_time' => '09:00:00',
        ]));

        $response->assertSessionHasErrors('requested_time');
    }

    // === Mensaje (opcional) ===

    public function test_mensaje_es_opcional(): void
    {
        $payload = $this->validPayload();
        unset($payload['message']);

        $response = $this->post('/citas', $payload);

        $response->assertSessionDoesntHaveErrors('message');
    }

    public function test_mensaje_no_puede_exceder_1000_caracteres(): void
    {
        $response = $this->post('/citas', array_merge($this->validPayload(), [
            'message' => str_repeat('A', 1001),
        ]));

        $response->assertSessionHasErrors('message');
    }

    // === Flujo exitoso ===

    public function test_formulario_valido_es_aceptado(): void
    {
        $response = $this->post('/citas', $this->validPayload());

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('appointments', [
            'patient_email' => 'maria@example.com',
            'status' => 'pendiente',
        ]);
    }
}
