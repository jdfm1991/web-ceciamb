<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $userId = $request->user()->id;

        // Próxima cita confirmada o pendiente
        $nextAppointment = Appointment::where('user_id', $userId)
            ->whereIn('status', ['confirmada', 'pendiente'])
            ->where('requested_date', '>=', today())
            ->orderBy('requested_date')
            ->orderBy('requested_time')
            ->with(['doctor:id,name', 'specialty:id,name'])
            ->first();

        // Estadísticas
        $stats = [
            'total' => Appointment::where('user_id', $userId)->count(),
            'pending' => Appointment::where('user_id', $userId)->where('status', 'pendiente')->count(),
            'confirmed' => Appointment::where('user_id', $userId)->where('status', 'confirmada')->count(),
            'completed' => Appointment::where('user_id', $userId)->where('status', 'completada')->count(),
        ];

        // Últimas 3 citas
        $recentAppointments = Appointment::where('user_id', $userId)
            ->with(['doctor:id,name', 'specialty:id,name'])
            ->orderBy('requested_date', 'desc')
            ->orderBy('requested_time', 'desc')
            ->limit(3)
            ->get()
            ->map(fn ($apt) => [
                'id' => $apt->id,
                'reference' => 'CITA-' . str_pad($apt->id, 6, '0', STR_PAD_LEFT),
                'doctor_name' => $apt->doctor?->name,
                'specialty_name' => $apt->specialty?->name,
                'requested_date' => $apt->requested_date->format('Y-m-d'),
                'requested_time' => $apt->requested_time,
                'status' => $apt->status,
            ]);

        return Inertia::render('Patient/Dashboard', [
            'nextAppointment' => $nextAppointment ? [
                'id' => $nextAppointment->id,
                'reference' => 'CITA-' . str_pad($nextAppointment->id, 6, '0', STR_PAD_LEFT),
                'doctor_name' => $nextAppointment->doctor?->name,
                'specialty_name' => $nextAppointment->specialty?->name,
                'requested_date' => $nextAppointment->requested_date->format('Y-m-d'),
                'requested_time' => $nextAppointment->requested_time,
                'status' => $nextAppointment->status,
            ] : null,
            'stats' => $stats,
            'recentAppointments' => $recentAppointments,
        ]);
    }
}