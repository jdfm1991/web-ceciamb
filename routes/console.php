<?php

use Illuminate\Support\Facades\Schedule;

// Limpieza de cuentas no activadas
Schedule::command('users:cleanup-unactivated')->hourly();

// Liberación de franjas expiradas
Schedule::command('appointments:release-expired')->hourly();
