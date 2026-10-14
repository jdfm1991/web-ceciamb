<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ClinicInfo extends Model
{
    use HasFactory;

    protected $table = 'clinic_info';

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Clave de caché.
     */
    public const CACHE_KEY = 'clinic_info';

    /**
     * Tiempo de caché en segundos (1 hora).
     */
    public const CACHE_TTL = 3600;

    /**
     * Devuelve todos los valores como un array asociativo, cacheado.
     */
    public static function allAsArray(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return static::query()
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    /**
     * Obtiene un valor específico por su clave.
     */
    public static function getValue(string $key, ?string $default = null): ?string
    {
        $all = static::allAsArray();
        return $all[$key] ?? $default;
    }

    /**
     * Resuelve la URL del logo.
     */
    public static function getLogoUrl(): string
    {
        $logo = static::getValue('logo', 'images/logo.png');

        if (str_starts_with($logo, 'clinic/')) {
            return asset('storage/' . $logo);
        }

        return asset($logo);
    }

    /**
     * Invalida la caché.
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Boot: invalida caché al guardar o eliminar.
     */
    protected static function booted(): void
    {
        static::saved(fn () => static::clearCache());
        static::deleted(fn () => static::clearCache());
    }
}