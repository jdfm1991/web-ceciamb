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
            ->orderByRaw("FIELD(category, 'medica', 'subespecialidad', 'profesion_salud', 'tecnica')")
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'description', 'category']);

        return Inertia::render('Specialties/Index', [
            'specialties' => $specialties,
            'categories' => Specialty::CATEGORIES,
        ]);
    }
}