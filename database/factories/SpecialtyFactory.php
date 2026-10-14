<?php

namespace Database\Factories;

use App\Models\Specialty;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SpecialtyFactory extends Factory
{
    protected $model = Specialty::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);
        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'category' => fake()->randomElement(['medica', 'subespecialidad', 'profesion_salud', 'tecnica']),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}