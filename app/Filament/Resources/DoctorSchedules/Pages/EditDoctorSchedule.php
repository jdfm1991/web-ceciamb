<?php

namespace App\Filament\Resources\DoctorSchedules\Pages;

use App\Filament\Resources\DoctorSchedules\DoctorScheduleResource;
use App\Models\DoctorSchedule;
use Illuminate\Validation\ValidationException;
use Filament\Resources\Pages\EditRecord;

class EditDoctorSchedule extends EditRecord
{
    protected static string $resource = DoctorScheduleResource::class;

    /**
     * Hook que se ejecuta antes de guardar los cambios.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->validateNoOverlap($data, $this->record->id);
        return $data;
    }

    protected function validateNoOverlap(array $data, ?int $ignoreId): void
    {
        if (
            empty($data['doctor_id']) ||
            $data['day_of_week'] === null ||
            empty($data['start_time']) ||
            empty($data['end_time'])
        ) {
            return;
        }

        if ($data['end_time'] <= $data['start_time']) {
            throw ValidationException::withMessages([
                'data.end_time' => 'La hora de fin debe ser posterior a la hora de inicio.',
            ]);
        }

        $query = DoctorSchedule::query()
            ->where('doctor_id', $data['doctor_id'])
            ->where('day_of_week', $data['day_of_week'])
            ->where(function ($q) use ($data) {
                $q->where('start_time', '<', $data['end_time'])
                    ->where('end_time', '>', $data['start_time']);
            });

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        $overlap = $query->first();

        if ($overlap) {
            throw ValidationException::withMessages([
                'data.start_time' => sprintf(
                    'Este bloque se solapa con el horario existente de %s a %s.',
                    substr($overlap->start_time, 0, 5),
                    substr($overlap->end_time, 0, 5)
                ),
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
