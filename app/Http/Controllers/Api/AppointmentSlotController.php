<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Services\AppointmentSlotService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentSlotController extends Controller
{
    public function __construct(
        protected AppointmentSlotService $slotService
    ) {}

    /**
     * Devuelve las franjas disponibles de un médico en una fecha.
     *
     * GET /api/doctors/{doctor}/available-slots?date=YYYY-MM-DD
     */
    public function index(Request $request, Doctor $doctor): JsonResponse
    {
        $request->validate([
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
        ]);

        $date = Carbon::createFromFormat('Y-m-d', $request->input('date'));

        $slots = $this->slotService->getAvailableSlots($doctor, $date);

        return response()->json([
            'doctor_id' => $doctor->id,
            'doctor_name' => $doctor->name,
            'date' => $date->toDateString(),
            'day_name' => \App\Models\DoctorSchedule::DAYS[$date->dayOfWeek] ?? '',
            'slots' => $slots,
            'total' => count($slots),
        ]);
    }
}