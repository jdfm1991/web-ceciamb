<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Services\AppointmentSlotService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class AppointmentController extends Controller
{
    public function __construct(
        protected AppointmentSlotService $slotService
    ) {}

    /**
     * Muestra el wizard de solicitud de citas.
     */
    public function create(Request $request): Response
    {
        $specialties = Specialty::active()
            ->orderByRaw("FIELD(category, 'medica', 'subespecialidad', 'profesion_salud', 'tecnica')")
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'category']);

        $preselectedDoctor = null;
        if ($doctorSlug = $request->query('doctor')) {
            $preselectedDoctor = Doctor::active()
                ->where('slug', $doctorSlug)
                ->with('specialties:id,name')
                ->first(['id', 'name', 'slug', 'photo']);
        }

        return Inertia::render('Appointments/Create', [
            'specialties' => $specialties,
            'preselectedDoctor' => $preselectedDoctor,
        ]);
    }

    /**
     * Guarda la solicitud de cita.
     */
    public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        return DB::transaction(function () use ($validated) {
            $requestedDate = Carbon::createFromFormat('Y-m-d', $validated['requested_date']);
            $requestedTime = $validated['requested_time'] . ':00'; // Normalizar a HH:MM:SS

            // Validar que el médico esté activo
            if (!empty($validated['doctor_id'])) {
                $doctor = Doctor::active()->find($validated['doctor_id']);
                if (!$doctor) {
                    return back()->withErrors([
                        'doctor_id' => 'El médico seleccionado no está disponible.',
                    ]);
                }

                // Verificar que la franja siga disponible
                $availableSlots = $this->slotService->getAvailableSlots($doctor, $requestedDate);

                $slotExists = collect($availableSlots)
                    ->contains('start_time_full', $requestedTime);

                if (!$slotExists) {
                    return back()->withErrors([
                        'requested_time' => 'La franja seleccionada ya no está disponible. Por favor, elige otra.',
                    ]);
                }
            }

            // Crear la cita
            $appointment = Appointment::create([
                'patient_name' => $validated['patient_name'],
                'patient_email' => $validated['patient_email'],
                'patient_phone' => $validated['patient_phone'],
                'user_id' => auth()->id(),
                'doctor_id' => $validated['doctor_id'] ?? null,
                'specialty_id' => $validated['specialty_id'] ?? null,
                'requested_date' => $validated['requested_date'],
                'requested_time' => $requestedTime,
                'message' => $validated['message'] ?? null,
                'status' => 'pendiente',
                'expires_at' => now()->addHours(24),
            ]);

            return redirect()
                ->route('appointments.success', ['appointment' => $appointment->id])
                ->with('success', 'Tu solicitud de cita fue registrada correctamente.');
        });
    }

    /**
     * Muestra la página de confirmación.
     */
    public function success(Appointment $appointment): Response
    {
        // Cargar relaciones necesarias
        $appointment->load(['doctor:id,name', 'specialty:id,name']);

        return Inertia::render('Appointments/Success', [
            'appointment' => [
                'id' => $appointment->id,
                'reference' => 'CITA-' . str_pad($appointment->id, 6, '0', STR_PAD_LEFT),
                'patient_name' => $appointment->patient_name,
                'patient_email' => $appointment->patient_email,
                'doctor_name' => $appointment->doctor?->name,
                'specialty_name' => $appointment->specialty?->name,
                'requested_date' => $appointment->requested_date->format('Y-m-d'),
                'requested_time' => $appointment->requested_time,
                'status' => $appointment->status,
                'expires_at' => $appointment->expires_at?->toIso8601String(),
            ],
        ]);
    }
}
