<?php

namespace App\Rules;

use App\Models\DoctorSchedule;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class NoScheduleOverlap implements ValidationRule, DataAwareRule
{
    protected array $data = [];

    protected ?int $ignoreId = null;

    /**
     * Permite ignorar un registro específico (útil al editar).
     */
    public function ignore(int $id): self
    {
        $this->ignoreId = $id;
        return $this;
    }

    /**
     * Recibe todos los datos del formulario.
     */
    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Valida el campo (en este caso, `start_time`).
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $doctorId   = $this->data['doctor_id'] ?? null;
        $dayOfWeek  = $this->data['day_of_week'] ?? null;
        $startTime  = $this->data['start_time'] ?? null;
        $endTime    = $this->data['end_time'] ?? null;

        // Si faltan datos, no podemos validar solapamiento.
        // Otras reglas (required) se encargarán de esos campos.
        if (!$doctorId || $dayOfWeek === null || !$startTime || !$endTime) {
            return;
        }

        // Validar que end_time sea mayor que start_time
        if ($endTime <= $startTime) {
            $fail('La hora de fin debe ser posterior a la hora de inicio.');
            return;
        }

        // Buscar solapamientos
        $query = DoctorSchedule::query()
            ->where('doctor_id', $doctorId)
            ->where('day_of_week', $dayOfWeek)
            ->where(function ($q) use ($startTime, $endTime) {
                // Solapamiento: nuevo.start < existente.end AND nuevo.end > existente.start
                $q->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime);
            });

        if ($this->ignoreId) {
            $query->where('id', '!=', $this->ignoreId);
        }

        $overlap = $query->first();

        if ($overlap) {
            $fail(sprintf(
                'Este bloque se solapa con el horario existente de %s a %s.',
                substr($overlap->start_time, 0, 5),
                substr($overlap->end_time, 0, 5)
            ));
        }
    }
}
