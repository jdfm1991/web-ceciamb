<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_con_cuenta_activada_funciona(): void
    {
        $user = User::factory()->create([
            'email' => 'paciente@test.com',
            'password' => Hash::make('password123'),
            'role' => 'paciente',
            'activation_token' => null,
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => 'paciente@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_falla_con_cuenta_no_activada(): void
    {
        $user = User::factory()->create([
            'email' => 'noactivada@test.com',
            'password' => Hash::make('password123'),
            'role' => 'paciente',
            'activation_token' => 'token-pendiente',
            'email_verified_at' => null,
        ]);

        $response = $this->post('/login', [
            'email' => 'noactivada@test.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_falla_con_password_incorrecta(): void
    {
        User::factory()->create([
            'email' => 'paciente@test.com',
            'password' => Hash::make('password123'),
            'role' => 'paciente',
            'activation_token' => null,
        ]);

        $response = $this->post('/login', [
            'email' => 'paciente@test.com',
            'password' => 'incorrecta',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_falla_con_email_inexistente(): void
    {
        $response = $this->post('/login', [
            'email' => 'noexiste@test.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_logout_cierra_la_sesion(): void
    {
        $user = User::factory()->create(['role' => 'paciente']);

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect(route('home'));
        $this->assertGuest();
    }

    public function test_admin_es_redirigido_al_panel_despues_del_login(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'activation_token' => null,
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin');
    }
}