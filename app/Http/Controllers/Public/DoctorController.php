<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DoctorController extends Controller
{
    public function index(Request $request): Response
    {
        $specialtySlug = $request->query('especialidad');

        $activeSpecialty = null;
        if ($specialtySlug) {
            $activeSpecialty = Specialty::where('slug', $specialtySlug)
                ->active()
                ->first(['id', 'name', 'slug']);
        }

        $doctors = Doctor::active()
            ->with(['specialties:id,name,slug'])
            ->when($specialtySlug, function ($query) use ($specialtySlug) {
                $query->whereHas('specialties', function ($q) use ($specialtySlug) {
                    $q->where('specialties.slug', $specialtySlug);
                });
            })
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'photo', 'biography']);

        return Inertia::render('Doctors/Index', [
            'doctors' => $doctors,
            'activeSpecialty' => $activeSpecialty,
        ]);
    }

    public function show(Doctor $doctor): Response
    {
        $doctor->load([
            'specialties:id,name,slug,category',
            'schedules' => function ($query) {
                $query->where('is_active', true)->orderBy('day_of_week')->orderBy('start_time');
            },
        ]);

        // Agrupar horarios por día de la semana
        $schedulesByDay = $doctor->schedules
            ->groupBy('day_of_week')
            ->map(function ($schedules) {
                return $schedules->map(function ($schedule) {
                    return [
                        'id' => $schedule->id,
                        'start_time' => $schedule->start_time,
                        'end_time' => $schedule->end_time,
                        'slot_duration_minutes' => $schedule->slot_duration_minutes,
                    ];
                })->values();
            });

        return Inertia::render('Doctors/Show', [
            'doctor' => [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'slug' => $doctor->slug,
                'photo' => $doctor->photo,
                'biography' => $doctor->biography,
                'specialties' => $doctor->specialties,
            ],
            'schedulesByDay' => $schedulesByDay,
            'dayNames' => \App\Models\DoctorSchedule::DAYS,
        ]);
    }
}
