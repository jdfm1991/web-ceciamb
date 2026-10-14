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

    public function ignore(?int $id): self
    {
        $this->ignoreId = $id;
        return $this;
    }

    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        \Log::info('NoScheduleOverlap validate ejecutado', [
            'data' => $this->data,
            'ignoreId' => $this->ignoreId,
        ]);
        $doctorId = $this->data['doctor_id'] ?? null;
        $dayOfWeek = $this->data['day_of_week'] ?? null;
        $startTime = $this->data['start_time'] ?? null;
        $endTime = $this->data['end_time'] ?? null;

        if (!$doctorId || $dayOfWeek === null || !$startTime || !$endTime) {
            return;
        }

        if ($endTime <= $startTime) {
            $fail('La hora de fin debe ser posterior a la hora de inicio.');
            return;
        }

        $query = DoctorSchedule::query()
            ->where('doctor_id', $doctorId)
            ->where('day_of_week', $dayOfWeek)
            ->where(function ($q) use ($startTime, $endTime) {
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
