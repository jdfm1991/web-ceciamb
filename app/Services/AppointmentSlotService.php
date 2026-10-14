<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AppointmentSlotService
{
    /**
     * Genera las franjas horarias disponibles para un médico en una fecha específica.
     *
     * @return array<int, array{start_time: string, end_time: string, start_time_full: string}>
     */
    public function getAvailableSlots(Doctor $doctor, Carbon $date): array
    {
        $dayOfWeek = $date->dayOfWeek; // 0=Domingo, 1=Lunes, ..., 6=Sábado

        // 1. Obtener los bloques horarios del médico para ese día de la semana
        $schedules = DoctorSchedule::query()
            ->where('doctor_id', $doctor->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->orderBy('start_time')
            ->get();

        if ($schedules->isEmpty()) {
            return [];
        }

        // 2. Obtener las citas que bloquean franjas ese día
        $blockedSlots = $this->getBlockedSlots($doctor, $date);

        // 3. Generar las franjas a partir de cada bloque
        $allSlots = collect();
        foreach ($schedules as $schedule) {
            $allSlots = $allSlots->merge(
                $this->generateSlotsFromSchedule($schedule, $date)
            );
        }

        // 4. Filtrar las franjas ocupadas y las pasadas
        $now = now();
        $availableSlots = $allSlots
            ->reject(function ($slot) use ($blockedSlots) {
                return in_array($slot['start_time_full'], $blockedSlots, true);
            })
            ->reject(function ($slot) use ($date, $now) {
                // Si la fecha es hoy, excluir franjas que ya pasaron
                if ($date->isToday()) {
                    $slotDateTime = Carbon::parse($date->toDateString() . ' ' . $slot['start_time_full']);
                    return $slotDateTime->lessThanOrEqualTo($now);
                }
                return false;
            })
            ->values()
            ->toArray();

        return $availableSlots;
    }

    /**
     * Genera las franjas de un bloque horario.
     *
     * @return Collection<int, array{start_time: string, end_time: string, start_time_full: string}>
     */
    protected function generateSlotsFromSchedule(DoctorSchedule $schedule, Carbon $date): Collection
    {
        $slots = collect();

        $slotDuration = $schedule->slot_duration_minutes ?: 30;

        $start = Carbon::parse($date->toDateString() . ' ' . $schedule->start_time);
        $end = Carbon::parse($date->toDateString() . ' ' . $schedule->end_time);

        while ($start->copy()->addMinutes($slotDuration)->lessThanOrEqualTo($end)) {
            $slots->push([
                'start_time' => $start->format('H:i'),
                'end_time' => $start->copy()->addMinutes($slotDuration)->format('H:i'),
                'start_time_full' => $start->format('H:i:s'),
            ]);

            $start->addMinutes($slotDuration);
        }

        return $slots;
    }

    /**
     * Obtiene las franjas bloqueadas por citas existentes.
     *
     * @return array<int, string> Array de start_time en formato HH:MM:SS
     */
    protected function getBlockedSlots(Doctor $doctor, Carbon $date): array
    {
        return Appointment::query()
            ->where('doctor_id', $doctor->id)
            ->whereDate('requested_date', $date->toDateString())
            ->whereNotIn('status', ['cancelada', 'no_asistio'])
            ->where(function ($query) {
                // Citas confirmadas o completadas siempre bloquean
                $query->whereIn('status', ['confirmada', 'completada'])
                    // Citas pendientes bloquean solo si no han expirado
                    ->orWhere(function ($q) {
                        $q->where('status', 'pendiente')
                          ->where(function ($qq) {
                              $qq->whereNull('expires_at')
                                 ->orWhere('expires_at', '>', now());
                          });
                    });
            })
            ->pluck('requested_time')
            ->map(function ($time) {
                // Normalizar a HH:MM:SS
                return Carbon::parse($time)->format('H:i:s');
            })
            ->toArray();
    }
}