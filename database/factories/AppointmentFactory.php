<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Specialty;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'patient_name' => fake()->name(),
            'patient_email' => fake()->unique()->safeEmail(),
            'patient_phone' => fake()->numerify('04########'),
            'doctor_id' => Doctor::factory(),
            'specialty_id' => Specialty::factory(),
            'requested_date' => fake()->dateTimeBetween('+1 day', '+30 days'),
            'requested_time' => fake()->randomElement(['08:00:00', '09:00:00', '10:00:00', '11:00:00']),
            'message' => fake()->optional()->sentence(),
            'status' => 'pendiente',
            'expires_at' => now()->addHours(24),
        ];
    }

    public function confirmed(): static
    {
        return $this->state(fn () => [
            'status' => 'confirmada',
            'expires_at' => null,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn () => [
            'status' => 'cancelada',
            'expires_at' => null,
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn () => [
            'status' => 'pendiente',
            'expires_at' => now()->subHours(2),
        ]);
    }
}