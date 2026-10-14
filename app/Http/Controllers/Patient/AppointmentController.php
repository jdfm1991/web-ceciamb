<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AppointmentController extends Controller
{
    public function index(Request $request): Response
    {
        $userId = $request->user()->id;
        $status = $request->query('status');

        $appointments = Appointment::where('user_id', $userId)
            ->when($status, fn ($q) => $q->where('status', $status))
            ->with(['doctor:id,name', 'specialty:id,name'])
            ->orderBy('requested_date', 'desc')
            ->orderBy('requested_time', 'desc')
            ->get()
            ->map(fn ($apt) => [
                'id' => $apt->id,
                'reference' => 'CITA-' . str_pad($apt->id, 6, '0', STR_PAD_LEFT),
                'doctor_name' => $apt->doctor?->name,
                'specialty_name' => $apt->specialty?->name,
                'requested_date' => $apt->requested_date->format('Y-m-d'),
                'requested_time' => $apt->requested_time,
                'status' => $apt->status,
                'can_cancel' => $apt->status === 'pendiente' && $apt->requested_date >= today(),
            ]);

        return Inertia::render('Patient/Appointments/Index', [
            'appointments' => $appointments,
            'activeStatus' => $status,
            'statuses' => Appointment::STATUSES,
        ]);
    }

    public function show(Request $request, Appointment $appointment): Response
    {
        // Verificar que la cita pertenezca al paciente autenticado
        if ($appointment->user_id !== $request->user()->id) {
            abort(404);
        }

        $appointment->load(['doctor:id,name,photo', 'specialty:id,name', 'parent:id,status']);

        return Inertia::render('Patient/Appointments/Show', [
            'appointment' => [
                'id' => $appointment->id,
                'reference' => 'CITA-' . str_pad($appointment->id, 6, '0', STR_PAD_LEFT),
                'patient_name' => $appointment->patient_name,
                'patient_email' => $appointment->patient_email,
                'patient_phone' => $appointment->patient_phone,
                'doctor_name' => $appointment->doctor?->name,
                'doctor_photo' => $appointment->doctor?->photo,
                'specialty_name' => $appointment->specialty?->name,
                'requested_date' => $appointment->requested_date->format('Y-m-d'),
                'requested_time' => $appointment->requested_time,
                'message' => $appointment->message,
                'status' => $appointment->status,
                'can_cancel' => $appointment->status === 'pendiente' && $appointment->requested_date >= today(),
            ],
        ]);
    }

    public function cancel(Request $request, Appointment $appointment): RedirectResponse
    {
        if ($appointment->user_id !== $request->user()->id) {
            abort(404);
        }

        if ($appointment->status !== 'pendiente') {
            return back()->with('error', 'Solo puedes cancelar citas pendientes.');
        }

        if ($appointment->requested_date < today()) {
            return back()->with('error', 'No puedes cancelar citas pasadas.');
        }

        $appointment->update([
            'status' => 'cancelada',
            'expires_at' => null,
        ]);

        return redirect()
            ->route('patient.appointments.index')
            ->with('success', 'Tu cita fue cancelada correctamente.');
    }
}