<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\Specialty;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $services = Service::active()
            ->orderBy('name')
            ->limit(6)
            ->get(['id', 'name', 'slug', 'description', 'image']);

        $specialties = Specialty::active()
            ->orderByRaw("FIELD(category, 'medica', 'subespecialidad', 'profesion_salud', 'tecnica')")
            ->orderBy('name')
            ->limit(12)
            ->get(['id', 'name', 'slug', 'category']);

        $doctors = Doctor::active()
            ->with('specialties:id,name')
            ->orderBy('name')
            ->limit(4)
            ->get(['id', 'name', 'slug', 'photo', 'biography']);

        return Inertia::render('Home/Index', [
            'services' => $services,
            'specialties' => $specialties,
            'doctors' => $doctors,
        ]);
    }
}