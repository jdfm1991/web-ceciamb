<?php

use Illuminate\Support\Facades\Schedule;

// Limpieza de cuentas no activadas
Schedule::command('users:cleanup-unactivated')->hourly();

// Liberación de franjas expiradas
Schedule::command('appointments:release-expired')->hourly();

// Recordatorios de citas (cada día a las 8:00 AM)
Schedule::command('appointments:send-reminders')->dailyAt('08:00');
