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
    ->withCount('doctors')
    ->orderByRaw("CASE category 
                        WHEN 'medica' THEN 1 
                        WHEN 'subespecialidad' THEN 2 
                        WHEN 'profesion_salud' THEN 3 
                        WHEN 'tecnica' THEN 4 
                        ELSE 5 
                    END")
                    ->orderBy('name')
                    ->get(['id', 'name', 'slug', 'description', 'category']);

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