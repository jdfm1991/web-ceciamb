<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Inertia\Inertia;
use Inertia\Response;

class SpecialtyController extends Controller
{
    public function index(): Response
    {
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

        return Inertia::render('Specialties/Index', [
            'specialties' => $specialties,
            'categories' => Specialty::CATEGORIES,
        ]);
    }
}
