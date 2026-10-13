<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicInfo extends Model
{
    use HasFactory;

    protected $table = 'clinic_info';

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Devuelve todos los valores como un array asociativo.
     * Ejemplo: ['name' => 'Hospital...', 'rif' => 'J-...']
     */
    public static function allAsArray(): array
    {
        return static::query()
            ->pluck('value', 'key')
            ->toArray();
    }

    /**
     * Obtiene un valor específico por su clave.
     */
    public static function getValue(string $key, ?string $default = null): ?string
    {
        return static::query()
            ->where('key', $key)
            ->value('value') ?? $default;
    }

    public static function getLogoUrl(): string
    {
        $logo = static::getValue('logo', 'images/logo.png');

        // Si el logo está en el disco public (storage), usar /storage/
        if (str_starts_with($logo, 'clinic/')) {
            return asset('storage/' . $logo);
        }

        // Si es el logo antiguo en public/images/, usar directamente
        return asset($logo);
    }
}
