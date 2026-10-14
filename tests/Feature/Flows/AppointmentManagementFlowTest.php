<?php

namespace Tests\Feature\Flows;

use App\Mail\AppointmentCancelledMail;
use App\Mail\AppointmentConfirmedMail;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AppointmentManagementFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_confirma_cita_y_paciente_recibe_correo(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $appointment = Appointment::factory()->create([
            'status' => 'pendiente',
            'expires_at' => now()->addHours(24),
        ]);

        // Simular la acción del panel (cambio de estado)
        $appointment->update(['status' => 'confirmada', 'expires_at' => null]);

        $appointment->refresh();
        $this->assertEquals('confirmada', $appointment->status);
        $this->assertNull($appointment->expires_at);

        // Verificar que el correo se envió al paciente
        Mail::assertSent(AppointmentConfirmedMail::class, function ($mail) use ($appointment) {
            return $mail->hasTo($appointment->patient_email);
        });
    }

    public function test_admin_cancela_cita_y_paciente_recibe_correo(): void
    {
        Mail::fake();

        $appointment = Appointment::factory()->create([
            'status' => 'pendiente',
        ]);

        $appointment->update(['status' => 'cancelada', 'expires_at' => null]);

        $appointment->refresh();
        $this->assertEquals('cancelada', $appointment->status);

        Mail::assertSent(AppointmentCancelledMail::class, function ($mail) use ($appointment) {
            return $mail->hasTo($appointment->patient_email);
        });
    }

    public function test_admin_completa_cita_no_envia_correo(): void
    {
        Mail::fake();

        $appointment = Appointment::factory()->create([
            'status' => 'confirmada',
        ]);

        $appointment->update(['status' => 'completada']);

        $appointment->refresh();
        $this->assertEquals('completada', $appointment->status);

        // No debe enviarse correo al completar
        Mail::assertNotSent(AppointmentConfirmedMail::class);
        Mail::assertNotSent(AppointmentCancelledMail::class);
    }
}