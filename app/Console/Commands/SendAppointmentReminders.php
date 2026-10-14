<?php

namespace App\Console\Commands;

use App\Mail\AppointmentReminderMail;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:send-reminders';

    protected $description = 'Envía recordatorios por correo a los pacientes con citas confirmadas para mañana';

    public function handle(): int
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $this->info("Buscando citas confirmadas para mañana ({$tomorrow})...");

        $appointments = Appointment::query()
            ->where('status', 'confirmada')
            ->whereDate('requested_date', $tomorrow)
            ->whereNull('reminder_sent_at')
            ->with(['doctor:id,name', 'specialty:id,name'])
            ->get();

        $count = $appointments->count();

        if ($count === 0) {
            $this->info('No hay citas para recordar mañana.');
            return self::SUCCESS;
        }

        $sent = 0;
        $failed = 0;

        foreach ($appointments as $appointment) {
            if (empty($appointment->patient_email)) {
                continue;
            }

            try {
                Mail::to($appointment->patient_email)->send(new AppointmentReminderMail($appointment));

                $appointment->update(['reminder_sent_at' => now()]);
                $sent++;

                $this->line("  → Recordatorio enviado a {$appointment->patient_email} (Cita #{$appointment->id}).");
            } catch (\Throwable $e) {
                report($e);
                $failed++;

                $this->error("  ✗ Error enviando recordatorio para Cita #{$appointment->id}: {$e->getMessage()}");
            }
        }

        $this->info("Recordatorios enviados: {$sent}. Fallidos: {$failed}.");

        Log::info('Recordatorios de citas procesados', [
            'date' => $tomorrow,
            'sent' => $sent,
            'failed' => $failed,
            'total' => $count,
        ]);

        return self::SUCCESS;
    }
}