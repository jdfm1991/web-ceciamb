<?php

namespace App\Observers;

use App\Models\DoctorSchedule;
use Illuminate\Validation\ValidationException;

class DoctorScheduleObserver
{
    /**
     * Se ejecuta antes de crear o actualizar un horario.
     * Valida que no haya solapamiento con otros bloques del mismo médico y día.
     */
    public function saving(DoctorSchedule $schedule): void
    {
        // Si faltan datos críticos, no validamos (las reglas del formulario lo harán)
        if (
            !$schedule->doctor_id ||
            $schedule->day_of_week === null ||
            !$schedule->start_time ||
            !$schedule->end_time
        ) {
            return;
        }

        // Validar que end_time > start_time
        if ($schedule->end_time <= $schedule->start_time) {
            throw ValidationException::withMessages([
                'end_time' => 'La hora de fin debe ser posterior a la hora de inicio.',
            ]);
        }

        // Buscar solapamientos
        $query = DoctorSchedule::query()
            ->where('doctor_id', $schedule->doctor_id)
            ->where('day_of_week', $schedule->day_of_week)
            ->where(function ($q) use ($schedule) {
                $q->where('start_time', '<', $schedule->end_time)
                  ->where('end_time', '>', $schedule->start_time);
            });

        // Ignorar el propio registro al editar
        if ($schedule->exists) {
            $query->where('id', '!=', $schedule->id);
        }

        $overlap = $query->first();

        \Log::info('Overlap: ' . $overlap);

        if ($overlap) {
            throw ValidationException::withMessages([
                'start_time' => sprintf(
                    'Este bloque se solapa con el horario existente de %s a %s.',
                    substr($overlap->start_time, 0, 5),
                    substr($overlap->end_time, 0, 5)
                ),
            ]);
        }
    }
}