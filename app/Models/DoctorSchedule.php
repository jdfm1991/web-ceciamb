<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'day_of_week',
        'start_time',
        'end_time',
        'slot_duration_minutes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'day_of_week' => 'integer',
        'slot_duration_minutes' => 'integer',
    ];

    /**
     * Nombres de los días de la semana.
     */
    public const DAYS = [
        0 => 'Domingo',
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado',
    ];

    /**
     * Relación con el médico.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Scope: solo horarios activos.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Accesor: nombre del día.
     */
    public function getDayNameAttribute(): string
    {
        return self::DAYS[$this->day_of_week] ?? 'Desconocido';
    }

    /**
     * Accesor: duración del bloque en minutos.
     */
    public function getDurationMinutesAttribute(): int
    {
        return Carbon::parse($this->start_time)
            ->diffInMinutes(Carbon::parse($this->end_time));
    }
}