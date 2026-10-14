<?php

namespace Tests\Feature\Flows;

use App\Mail\NewAppointmentRequestMail;
use App\Models\Appointment;
use App\Models\ClinicInfo;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Specialty;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AppointmentRequestFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Fijar "ahora" para tests deterministas
        Carbon::setTestNow('2026-11-02 08:00:00'); // Lunes

        // Crear información institucional
        ClinicInfo::updateOrCreate(
            ['key' => 'email'],
            ['value' => 'recepcion@ceciamb.test']
        );
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow(null);
        parent::tearDown();
    }

    public function test_flujo_completo_de_solicitud_de_cita(): void
    {
        Mail::fake();

        // === 1. Preparar datos ===
        $specialty = Specialty::factory()->create([
            'name' => 'Cardiología',
            'slug' => 'cardiologia',
            'category' => 'medica',
            'is_active' => true,
        ]);

        $doctor = Doctor::factory()->create([
            'name' => 'Dr. Juan Pérez',
            'slug' => 'dr-juan-perez',
            'is_active' => true,
        ]);

        $doctor->specialties()->attach($specialty->id);

        // Horario: Lunes de 8:00 a 12:00
        DoctorSchedule::create([
            'doctor_id' => $doctor->id,
            'day_of_week' => Carbon::MONDAY,
            'start_time' => '08:00:00',
            'end_time' => '12:00:00',
            'slot_duration_minutes' => 30,
            'is_active' => true,
        ]);

        // === 2. Verificar disponibilidad de franjas ===
        $response = $this->getJson("/api/doctors/{$doctor->id}/available-slots?date=2026-11-09");
        $response->assertSuccessful();
        $response->assertJsonStructure([
            'doctor_id',
            'doctor_name',
            'date',
            'day_name',
            'slots',
            'total',
        ]);

        $slots = $response->json('slots');
        $this->assertNotEmpty($slots);
        $this->assertEquals('08:00', $slots[0]['start_time']);

        // === 3. Paciente envía la solicitud ===
        $response = $this->post('/citas', [
            'patient_name' => 'María González',
            'patient_email' => 'maria@example.com',
            'patient_phone' => '04161234567',
            'doctor_id' => $doctor->id,
            'specialty_id' => $specialty->id,
            'requested_date' => '2026-11-09',
            'requested_time' => '09:00',
            'message' => 'Consulta de rutina',
        ]);

        $response->assertRedirect();

        // === 4. Verificar que la cita se creó ===
        $appointment = Appointment::where('patient_email', 'maria@example.com')->first();
        $this->assertNotNull($appointment);
        $this->assertEquals('pendiente', $appointment->status);
        $this->assertEquals($doctor->id, $appointment->doctor_id);
        $this->assertEquals($specialty->id, $appointment->specialty_id);
        $this->assertEquals('09:00:00', $appointment->requested_time);
        $this->assertNotNull($appointment->expires_at);
        $this->assertTrue($appointment->expires_at->isFuture());

        // === 5. Verificar que recepción recibió el correo ===
        Mail::assertSent(NewAppointmentRequestMail::class, function ($mail) {
            return $mail->hasTo('recepcion@ceciamb.test');
        });

        // === 6. Verificar que la franja ya no está disponible ===
        $response = $this->getJson("/api/doctors/{$doctor->id}/available-slots?date=2026-11-09");
        $slots = $response->json('slots');
        $startTimes = array_column($slots, 'start_time');
        $this->assertNotContains('09:00', $startTimes);
    }

    public function test_no_permite_dos_citas_en_la_misma_franja(): void
    {
        Mail::fake();

        $doctor = Doctor::factory()->create(['is_active' => true]);
        $specialty = Specialty::factory()->create(['is_active' => true]);
        $doctor->specialties()->attach($specialty->id);

        DoctorSchedule::create([
            'doctor_id' => $doctor->id,
            'day_of_week' => Carbon::MONDAY,
            'start_time' => '08:00:00',
            'end_time' => '12:00:00',
            'slot_duration_minutes' => 30,
            'is_active' => true,
        ]);

        // Primera cita
        $this->post('/citas', [
            'patient_name' => 'Paciente 1',
            'patient_email' => 'p1@example.com',
            'patient_phone' => '04141234567',
            'doctor_id' => $doctor->id,
            'specialty_id' => $specialty->id,
            'requested_date' => '2026-11-09',
            'requested_time' => '09:00',
        ]);

        // Segunda cita a la misma franja
        $response = $this->post('/citas', [
            'patient_name' => 'Paciente 2',
            'patient_email' => 'p2@example.com',
            'patient_phone' => '04141234568',
            'doctor_id' => $doctor->id,
            'specialty_id' => $specialty->id,
            'requested_date' => '2026-11-09',
            'requested_time' => '09:00',
        ]);

        $response->assertSessionHasErrors('requested_time');

        // Solo debe existir una cita
        $this->assertEquals(1, Appointment::count());
    }
}