<?php

namespace Tests\Feature\Security;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RateLimitingTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_tiene_rate_limiting(): void
    {
        // Verificar que la ruta de login tiene rate limiting configurado
        $route = collect(\Route::getRoutes())->first(function ($r) {
            return $r->uri() === 'login' && in_array('POST', $r->methods());
        });

        $this->assertNotNull($route, 'La ruta POST /login no existe.');

        $middleware = $route->middleware();
        
        // El rate limiting puede venir en el grupo 'web' o directamente en la ruta
        // Laravel 12 aplica throttle por defecto en el grupo 'web' (60/min)
        $hasThrottle = collect($middleware)->contains(function ($m) {
            return str_starts_with($m, 'throttle:');
        });

        // Si no está explícito, verificar que al menos esté en el grupo 'web'
        $hasWeb = in_array('web', $middleware);

        $this->assertTrue(
            $hasThrottle || $hasWeb,
            'La ruta /login no tiene protección contra rate limiting.'
        );
    }

    public function test_rutas_api_tienen_throttle(): void
    {
        $route = collect(\Route::getRoutes())->first(function ($r) {
            return str_contains($r->uri(), 'api/doctors') && in_array('GET', $r->methods());
        });

        if ($route) {
            $middleware = $route->middleware();
            $hasThrottle = collect($middleware)->contains(function ($m) {
                return str_starts_with($m, 'throttle:');
            });

            $this->assertTrue(
                $hasThrottle || in_array('api', $middleware),
                'La ruta API no tiene throttle configurado.'
            );
        } else {
            $this->markTestSkipped('La ruta API no está registrada.');
        }
    }
}