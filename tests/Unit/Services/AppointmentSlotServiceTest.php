<?php

namespace Tests\Unit\Services;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Services\AppointmentSlotService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentSlotServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AppointmentSlotService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AppointmentSlotService();
    }

    public function test_devuelve_franjas_para_un_dia_con_horario(): void
    {
        $doctor = Doctor::factory()->create(['is_active' => true]);

        DoctorSchedule::create([
            'doctor_id' => $doctor->id,
            'day_of_week' => Carbon::MONDAY,
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'slot_duration_minutes' => 30,
            'is_active' => true,
        ]);

        // Próximo lunes
        $nextMonday = Carbon::now()->next(Carbon::MONDAY);

        $slots = $this->service->getAvailableSlots($doctor, $nextMonday);

        // 08:00, 08:30, 09:00, 09:30 = 4 franjas
        $this->assertCount(4, $slots);
        $this->assertEquals('08:00', $slots[0]['start_time']);
        $this->assertEquals('09:30', $slots[3]['start_time']);
    }

    public function test_excluye_franjas_ocupadas_por_citas_confirmadas(): void
    {
        $doctor = Doctor::factory()->create(['is_active' => true]);

        DoctorSchedule::create([
            'doctor_id' => $doctor->id,
            'day_of_week' => Carbon::MONDAY,
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'slot_duration_minutes' => 30,
            'is_active' => true,
        ]);

        $nextMonday = Carbon::now()->next(Carbon::MONDAY);

        Appointment::create([
            'patient_name' => 'Test',
            'patient_email' => 'test@test.com',
            'patient_phone' => '04141234567',
            'doctor_id' => $doctor->id,
            'requested_date' => $nextMonday->toDateString(),
            'requested_time' => '08:30:00',
            'status' => 'confirmada',
        ]);

        $slots = $this->service->getAvailableSlots($doctor, $nextMonday);

        // De 4 franjas, una está ocupada → 3 disponibles
        $this->assertCount(3, $slots);

        $startTimes = array_column($slots, 'start_time');
        $this->assertNotContains('08:30', $startTimes);
    }

    public function test_devuelve_vacio_si_no_hay_horarios(): void
    {
        $doctor = Doctor::factory()->create(['is_active' => true]);
        $nextMonday = Carbon::now()->next(Carbon::MONDAY);

        $slots = $this->service->getAvailableSlots($doctor, $nextMonday);

        $this->assertEmpty($slots);
    }
}