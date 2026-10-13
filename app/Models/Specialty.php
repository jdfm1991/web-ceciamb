<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Specialty extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Categorías válidas.
     */
    public const CATEGORIES = [
        'medica' => 'Especialidad Médica',
        'subespecialidad' => 'Subespecialidad',
        'profesion_salud' => 'Profesión de la Salud',
        'tecnica' => 'Técnica / Apoyo',
    ];

    /**
     * Relación N:M con doctores.
     */
    public function doctors(): BelongsToMany
    {
        return $this->belongsToMany(Doctor::class, 'doctor_specialty')
            ->withTimestamps();
    }

    /**
     * Scope: solo especialidades activas.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Boot: genera el slug automáticamente.
     */
    protected static function booted(): void
    {
        static::creating(function (Specialty $specialty) {
            if (empty($specialty->slug)) {
                $specialty->slug = Str::slug($specialty->name);
            }
        });

        static::updating(function (Specialty $specialty) {
            if ($specialty->isDirty('name') && empty($specialty->slug)) {
                $specialty->slug = Str::slug($specialty->name);
            }
        });
    }
}