<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Illuminate\Http\JsonResponse;

class SpecialtyController extends Controller
{
    /**
     * Devuelve los médicos activos de una especialidad.
     *
     * GET /api/specialties/{specialty}/doctors
     */
    public function doctors(Specialty $specialty): JsonResponse
    {
        $doctors = $specialty->doctors()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['doctors.id', 'doctors.name', 'doctors.slug', 'doctors.photo']);

        return response()->json([
            'specialty_id' => $specialty->id,
            'specialty_name' => $specialty->name,
            'doctors' => $doctors,
        ]);
    }
}