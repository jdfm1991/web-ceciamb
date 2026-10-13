<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'photo',
        'biography',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relación N:M con especialidades.
     */
    public function specialties(): BelongsToMany
    {
        return $this->belongsToMany(Specialty::class, 'doctor_specialty')
            ->withTimestamps();
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    /**
     * Scope: solo médicos activos.
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
        static::creating(function (Doctor $doctor) {
            if (empty($doctor->slug)) {
                $doctor->slug = Str::slug($doctor->name);
            }
        });

        static::updating(function (Doctor $doctor) {
            if ($doctor->isDirty('name') && empty($doctor->slug)) {
                $doctor->slug = Str::slug($doctor->name);
            }
        });
    }
}
