<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Recepcionista de prueba
        User::updateOrCreate(
            ['email' => 'recepcion@ceciamb.test'],
            [
                'name' => 'Recepción Ceciamb',
                'password' => Hash::make('password'),
                'role' => 'recepcionista',
                'email_verified_at' => now(),
                'activation_token' => null,
                'activation_expires_at' => null,
            ]
        );

        // Paciente de prueba (activado)
        User::updateOrCreate(
            ['email' => 'paciente@ceciamb.test'],
            [
                'name' => 'Paciente de Prueba',
                'password' => Hash::make('password'),
                'role' => 'paciente',
                'email_verified_at' => now(),
                'activation_token' => null,
                'activation_expires_at' => null,
            ]
        );

        // Paciente de prueba (sin activar, con token expirado)
        User::updateOrCreate(
            ['email' => 'paciente-no-activado@ceciamb.test'],
            [
                'name' => 'Paciente No Activado',
                'password' => Hash::make('password'),
                'role' => 'paciente',
                'activation_token' => \Illuminate\Support\Str::random(64),
                'activation_expires_at' => now()->subHours(2),
            ]
        );
    }
}