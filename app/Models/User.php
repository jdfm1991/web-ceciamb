<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Roles disponibles.
     */
    public const ROLES = [
        'admin' => 'Administrador',
        'recepcionista' => 'Recepcionista',
        'paciente' => 'Paciente',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'activation_token',
        'activation_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'activation_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'activation_expires_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * ¿Es administrador?
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * ¿Es recepcionista?
     */
    public function isRecepcionista(): bool
    {
        return $this->role === 'recepcionista';
    }

    /**
     * ¿Es paciente?
     */
    public function isPaciente(): bool
    {
        return $this->role === 'paciente';
    }

    /**
     * ¿Está la cuenta activada?
     */
    public function isActivated(): bool
    {
        return is_null($this->activation_token);
    }

    /**
     * ¿Ha expirado el token de activación?
     */
    public function isActivationExpired(): bool
    {
        return $this->activation_expires_at && $this->activation_expires_at->isPast();
    }

    /**
     * Scope: solo pacientes.
     */
    public function scopePacientes($query)
    {
        return $query->where('role', 'paciente');
    }

    /**
     * Scope: solo cuentas no activadas.
     */
    public function scopeNotActivated($query)
    {
        return $query->whereNotNull('activation_token');
    }

    /**
     * Relación con citas (se usará en Sub-fase 2.7).
     */
    // public function appointments(): HasMany
    // {
    //     return $this->hasMany(Appointment::class);
    // }
}