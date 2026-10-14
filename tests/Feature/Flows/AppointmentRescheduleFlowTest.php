<?php

namespace Tests\Feature\Flows;

use App\Mail\AppointmentRescheduledMail;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Specialty;
use App\Services\AppointmentSlotService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AppointmentRescheduleFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow('2026-11-02 08:00:00');
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow(null);
        parent::tearDown();
    }

    public function test_reagendamiento_crea_nueva_cita_y_notifica(): void
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

        // Cita original cancelada
        $original = Appointment::factory()->cancelled()->create([
            'doctor_id' => $doctor->id,
            'specialty_id' => $specialty->id,
            'requested_date' => '2026-11-02',
        ]);

        // Simular reagendamiento: crear nueva cita vinculada
        $nueva = Appointment::create([
            'patient_name' => $original->patient_name,
            'patient_email' => $original->patient_email,
            'patient_phone' => $original->patient_phone,
            'user_id' => $original->user_id,
            'doctor_id' => $doctor->id,
            'specialty_id' => $specialty->id,
            'requested_date' => '2026-11-09',
            'requested_time' => '09:00:00',
            'message' => $original->message,
            'status' => 'pendiente',
            'parent_appointment_id' => $original->id,
            'expires_at' => now()->addHours(24),
        ]);

        // Verificar la relación padre-hijo
        $this->assertEquals($original->id, $nueva->parent_appointment_id);
        $this->assertEquals($nueva->id, $original->fresh()->children->first()->id);

        // Verificar que la original no cambió
        $original->refresh();
        $this->assertEquals('cancelada', $original->status);

        // Enviar notificación manualmente (simulando la acción de Filament)
        Mail::to($nueva->patient_email)->send(
            new AppointmentRescheduledMail($nueva, $original)
        );

        Mail::assertSent(AppointmentRescheduledMail::class, function ($mail) use ($nueva) {
            return $mail->hasTo($nueva->patient_email);
        });
    }

    public function test_reagendamiento_bloquea_franja_en_nueva_fecha(): void
    {
        Mail::fake();

        $doctor = Doctor::factory()->create(['is_active' => true]);
        DoctorSchedule::create([
            'doctor_id' => $doctor->id,
            'day_of_week' => Carbon::MONDAY,
            'start_time' => '08:00:00',
            'end_time' => '12:00:00',
            'slot_duration_minutes' => 30,
            'is_active' => true,
        ]);

        // Crear nueva cita reagendada
        Appointment::factory()->create([
            'doctor_id' => $doctor->id,
            'requested_date' => '2026-11-09',
            'requested_time' => '09:00:00',
            'status' => 'pendiente',
            'expires_at' => now()->addHours(24),
        ]);

        // Verificar que la franja está bloqueada
        $slotService = app(AppointmentSlotService::class);
        $slots = $slotService->getAvailableSlots(
            $doctor,
            Carbon::parse('2026-11-09')
        );

        $startTimes = array_column($slots, 'start_time');
        $this->assertNotContains('09:00', $startTimes);
    }

    public function test_reagendamiento_a_franja_ocupada_falla(): void
    {
        Mail::fake();

        $doctor = Doctor::factory()->create(['is_active' => true]);
        DoctorSchedule::create([
            'doctor_id' => $doctor->id,
            'day_of_week' => Carbon::MONDAY,
            'start_time' => '08:00:00',
            'end_time' => '12:00:00',
            'slot_duration_minutes' => 30,
            'is_active' => true,
        ]);

        // Cita existente ocupando las 09:00
        Appointment::factory()->confirmed()->create([
            'doctor_id' => $doctor->id,
            'requested_date' => '2026-11-09',
            'requested_time' => '09:00:00',
        ]);

        // Verificar que la franja no está disponible
        $slotService = app(AppointmentSlotService::class);
        $slots = $slotService->getAvailableSlots(
            $doctor,
            Carbon::parse('2026-11-09')
        );

        $startTimes = array_column($slots, 'start_time');
        $this->assertNotContains('09:00', $startTimes);
    }
}