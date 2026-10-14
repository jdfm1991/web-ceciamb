<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    public function index(): Response
    {
        $services = Service::active()
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'description', 'image']);

        return Inertia::render('Services/Index', [
            'services' => $services,
        ]);
    }
}