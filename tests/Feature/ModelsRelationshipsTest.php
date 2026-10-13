<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\ClinicInfo;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Service;
use App\Models\Specialty;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\AppointmentSeeder;
use Database\Seeders\ClinicInfoSeeder;
use Database\Seeders\DoctorScheduleSeeder;
use Database\Seeders\DoctorSeeder;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\SpecialtySeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ModelsRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ejecutar seeders para tener datos de prueba
        $this->seed(ClinicInfoSeeder::class);
        $this->seed(SpecialtySeeder::class);
        $this->seed(DoctorSeeder::class);
        $this->seed(DoctorScheduleSeeder::class);
        $this->seed(ServiceSeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(AppointmentSeeder::class);
    }

    #[Test]
    public function clinic_info_devuelve_array_asociativo(): void
    {
        $info = ClinicInfo::allAsArray();

        $this->assertIsArray($info);
        $this->assertArrayHasKey('name', $info);
        $this->assertArrayHasKey('rif', $info);
        $this->assertEquals('Hospital de Clínicas de Ceciamb, C.A', $info['name']);
    }

    #[Test]
    public function doctor_tiene_muchas_especialidades(): void
    {
        $doctor = Doctor::where('name', 'Dr. Juan Pérez')->first();

        $this->assertNotNull($doctor);
        $this->assertCount(2, $doctor->specialties);
        $this->assertContains('Internista', $doctor->specialties->pluck('name'));
        $this->assertContains('Intensivista', $doctor->specialties->pluck('name'));
    }

    #[Test]
    public function specialty_tiene_muchos_doctores(): void
    {
        $specialty = Specialty::where('name', 'Pediatra')->first();

        $this->assertNotNull($specialty);
        $this->assertCount(1, $specialty->doctors);
        $this->assertEquals('Dra. María González', $specialty->doctors->first()->name);
    }

    #[Test]
    public function doctor_tiene_muchos_horarios(): void
    {
        $doctor = Doctor::where('name', 'Dr. Juan Pérez')->first();

        $this->assertCount(10, $doctor->schedules);
    }

    #[Test]
    public function doctor_schedule_pertenece_a_doctor(): void
    {
        $schedule = DoctorSchedule::first();

        $this->assertNotNull($schedule->doctor);
        $this->assertInstanceOf(Doctor::class, $schedule->doctor);
    }

    #[Test]
    public function doctor_schedule_accesor_day_name_funciona(): void
    {
        $schedule = DoctorSchedule::where('day_of_week', Carbon::MONDAY)->first();

        $this->assertEquals('Lunes', $schedule->day_name);
    }

    #[Test]
    public function doctor_schedule_accesor_duration_minutes_funciona(): void
    {
        $schedule = DoctorSchedule::where('start_time', '08:00:00')->first();

        $this->assertEquals(240, $schedule->duration_minutes);
    }

    #[Test]
    public function service_scope_active_funciona(): void
    {
        $this->assertEquals(6, Service::active()->count());
    }

    #[Test]
    public function user_metodos_de_rol_funcionan(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($admin->isPaciente());

        $recepcionista = User::where('role', 'recepcionista')->first();
        $this->assertTrue($recepcionista->isRecepcionista());

        $paciente = User::where('role', 'paciente')->first();
        $this->assertTrue($paciente->isPaciente());
    }

    #[Test]
    public function user_metodos_de_activacion_funcionan(): void
    {
        $noActivado = User::where('email', 'paciente-no-activado@ceciamb.test')->first();

        $this->assertFalse($noActivado->isActivated());
        $this->assertTrue($noActivado->isActivationExpired());
    }

    #[Test]
    public function user_scope_not_activated_funciona(): void
    {
        $this->assertEquals(1, User::notActivated()->count());
    }

    #[Test]
    public function appointment_pertenece_a_doctor(): void
    {
        $appointment = Appointment::whereNotNull('doctor_id')->first();

        $this->assertInstanceOf(Doctor::class, $appointment->doctor);
    }

    #[Test]
    public function appointment_pertenece_a_user(): void
    {
        $appointment = Appointment::whereNotNull('user_id')->first();

        $this->assertInstanceOf(User::class, $appointment->user);
    }

    #[Test]
    public function appointment_scope_expired_funciona(): void
    {
        $this->assertEquals(1, Appointment::expired()->count());
    }

    #[Test]
    public function appointment_metodo_is_expired_funciona(): void
    {
        $expirada = Appointment::where('patient_email', 'paciente-no-activado@ceciamb.test')->first();
        $this->assertTrue($expirada->isExpired());

        $noExpirada = Appointment::where('status', 'confirmada')->first();
        $this->assertFalse($noExpirada->isExpired());
    }

    #[Test]
    public function appointment_can_transition_to_funciona(): void
    {
        $pendiente = Appointment::where('status', 'pendiente')->first();

        $this->assertTrue($pendiente->canTransitionTo('confirmada'));
        $this->assertTrue($pendiente->canTransitionTo('cancelada'));
        $this->assertFalse($pendiente->canTransitionTo('completada'));
        $this->assertFalse($pendiente->canTransitionTo('no_asistio'));

        $confirmada = Appointment::where('status', 'confirmada')->first();

        $this->assertTrue($confirmada->canTransitionTo('completada'));
        $this->assertTrue($confirmada->canTransitionTo('no_asistio'));
        $this->assertTrue($confirmada->canTransitionTo('cancelada'));
        $this->assertFalse($confirmada->canTransitionTo('pendiente'));
    }

    #[Test]
    public function appointment_relacion_parent_children_funciona(): void
    {
        // Crear una cita padre
        $padre = Appointment::create([
            'patient_name' => 'Test Padre',
            'patient_email' => 'padre@test.com',
            'patient_phone' => '04141234567',
            'requested_date' => now()->addDays(7),
            'requested_time' => '10:00:00',
            'status' => 'cancelada',
        ]);

        // Crear una cita hija (reagendamiento)
        $hijo = Appointment::create([
            'patient_name' => 'Test Padre',
            'patient_email' => 'padre@test.com',
            'patient_phone' => '04141234567',
            'requested_date' => now()->addDays(14),
            'requested_time' => '11:00:00',
            'status' => 'pendiente',
            'parent_appointment_id' => $padre->id,
        ]);

        $this->assertEquals($padre->id, $hijo->parent->id);
        $this->assertCount(1, $padre->children);
    }
}
