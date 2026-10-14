<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ReleaseExpiredAppointments extends Command
{
    protected $signature = 'appointments:release-expired';

    protected $description = 'Libera las franjas horarias de citas pendientes que han expirado (expires_at < now())';

    public function handle(): int
    {
        $this->info('Buscando citas pendientes expiradas...');

        $expiredAppointments = Appointment::query()
            ->where('status', 'pendiente')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->get();

        $count = $expiredAppointments->count();

        if ($count === 0) {
            $this->info('No hay citas expiradas para liberar.');
            return self::SUCCESS;
        }

        foreach ($expiredAppointments as $appointment) {
            // saveQuietly() no dispara eventos del Observer
            $appointment->status = 'cancelada';
            $appointment->expires_at = null;
            $appointment->saveQuietly();

            $this->line("  → Cita #{$appointment->id} ({$appointment->patient_name}) liberada.");
        }

        $this->info("Se liberaron {$count} citas expiradas.");

        Log::info('Citas expiradas liberadas', [
            'count' => $count,
            'appointment_ids' => $expiredAppointments->pluck('id')->toArray(),
        ]);

        return self::SUCCESS;
    }
}
