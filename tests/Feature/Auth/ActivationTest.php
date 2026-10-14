<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivationTest extends TestCase
{
    use RefreshDatabase;

    public function test_activacion_con_token_valido(): void
    {
        $user = User::factory()->create([
            'role' => 'paciente',
            'activation_token' => 'token-valido-123',
            'activation_expires_at' => now()->addHours(48),
            'email_verified_at' => null,
        ]);

        $response = $this->get('/activar-cuenta/token-valido-123');

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertNull($user->activation_token);
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_activacion_falla_con_token_invalido(): void
    {
        $response = $this->get('/activar-cuenta/token-inexistente');

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
    }

    public function test_activacion_falla_con_token_expirado(): void
    {
        $user = User::factory()->create([
            'role' => 'paciente',
            'activation_token' => 'token-expirado',
            'activation_expires_at' => now()->subHours(2),
            'email_verified_at' => null,
        ]);

        $response = $this->get('/activar-cuenta/token-expirado');

        $response->assertRedirect(route('activation.resend', ['email' => $user->email]));
        $response->assertSessionHas('error');

        $user->refresh();
        $this->assertNotNull($user->activation_token);
    }

    public function test_reenvio_de_activacion_genera_nuevo_token(): void
    {
        $user = User::factory()->create([
            'role' => 'paciente',
            'activation_token' => 'token-antiguo',
            'activation_expires_at' => now()->subHours(2),
        ]);

        $oldToken = $user->activation_token;

        $response = $this->post('/reenviar-activacion', [
            'email' => $user->email,
        ]);

        $response->assertRedirect();

        $user->refresh();
        $this->assertNotEquals($oldToken, $user->activation_token);
        $this->assertTrue($user->activation_expires_at->isFuture());
    }
}