<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_name',
        'patient_email',
        'patient_phone',
        'user_id',
        'doctor_id',
        'specialty_id',
        'requested_date',
        'requested_time',
        'message',
        'status',
        'parent_appointment_id',
        'expires_at',
    ];

    protected $casts = [
        'requested_date' => 'date',
        'expires_at' => 'datetime',
    ];

    /**
     * Estados disponibles.
     */
    public const STATUSES = [
        'pendiente' => 'Pendiente',
        'confirmada' => 'Confirmada',
        'cancelada' => 'Cancelada',
        'completada' => 'Completada',
        'no_asistio' => 'No Asistió',
    ];

    /**
     * Transiciones permitidas entre estados.
     * [estado_actual => [estados_a_los_que_puede_transicionar]]
     */
    public const TRANSITIONS = [
        'pendiente' => ['confirmada', 'cancelada'],
        'confirmada' => ['completada', 'no_asistio', 'cancelada'],
        'cancelada' => [],
        'completada' => [],
        'no_asistio' => [],
    ];

    /**
     * Relación con el usuario registrado (opcional).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el médico.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Relación con la especialidad.
     */
    public function specialty(): BelongsTo
    {
        return $this->belongsTo(Specialty::class);
    }

    /**
     * Relación con la cita padre (si es un reagendamiento).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'parent_appointment_id');
    }

    /**
     * Relación con las citas hijas (reagendamientos).
     */
    public function children(): HasMany
    {
        return $this->hasMany(Appointment::class, 'parent_appointment_id');
    }

    /**
     * Scope: solo citas pendientes.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pendiente');
    }

    /**
     * Scope: citas expiradas (pendientes con expires_at pasado).
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'pendiente')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now());
    }

    /**
     * ¿Está la cita expirada?
     */
    public function isExpired(): bool
    {
        return $this->status === 'pendiente'
            && $this->expires_at
            && $this->expires_at->isPast();
    }

    /**
     * ¿Puede transicionar al estado dado?
     */
    public function canTransitionTo(string $newStatus): bool
    {
        $allowed = self::TRANSITIONS[$this->status] ?? [];
        return in_array($newStatus, $allowed, true);
    }
}