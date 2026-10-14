<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CleanupUnactivatedUsers extends Command
{
    protected $signature = 'users:cleanup-unactivated';

    protected $description = 'Elimina cuentas de pacientes no activadas después de 48 horas';

    public function handle(): int
    {
        $count = User::query()
            ->where('role', 'paciente')
            ->whereNotNull('activation_token')
            ->where('activation_expires_at', '<', now())
            ->delete();

        $this->info("Se eliminaron {$count} cuentas no activadas.");

        return self::SUCCESS;
    }
}