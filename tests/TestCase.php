<?php

namespace Tests;

use App\Models\Doctor;
use App\Models\Specialty;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function test_home_responde_en_menos_de_500ms(): void
    {
        Specialty::factory()->count(5)->create(['is_active' => true]);
        Doctor::factory()->count(3)->create(['is_active' => true]);

        $start = microtime(true);
        $this->get('/');
        $duration = (microtime(true) - $start) * 1000; // ms

        $this->assertLessThan(
            500,
            $duration,
            "La home tardó {$duration}ms en responder."
        );
    }
}
