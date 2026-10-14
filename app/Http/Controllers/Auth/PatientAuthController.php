<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterPatientRequest;
use App\Mail\PatientActivationMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class PatientAuthController extends Controller
{
    /**
     * Muestra el formulario de registro.
     */
    public function showRegister(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Crea la cuenta y envía el correo de activación.
     */
    public function register(RegisterPatientRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'paciente',
            'activation_token' => Str::random(64),
            'activation_expires_at' => now()->addHours(48),
            'email_verified_at' => null,
        ]);

        $this->sendActivationEmail($user);

        return redirect()
            ->route('activation.pending', ['email' => $user->email])
            ->with('success', 'Tu cuenta fue creada. Revisa tu correo para activarla.');
    }

    /**
     * Activa la cuenta usando el token.
     */
    public function activate(string $token): RedirectResponse
    {
        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            return redirect()
                ->route('login')
                ->with('error', 'El enlace de activación no es válido.');
        }

        if ($user->activation_expires_at && $user->activation_expires_at->isPast()) {
            return redirect()
                ->route('activation.resend', ['email' => $user->email])
                ->with('error', 'El enlace de activación ha expirado. Solicita uno nuevo.');
        }

        $user->update([
            'activation_token' => null,
            'activation_expires_at' => null,
            'email_verified_at' => now(),
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Tu cuenta ha sido activada. Ya puedes iniciar sesión.');
    }

    /**
     * Muestra la página de "revisa tu correo".
     */
    public function pending(Request $request): Response
    {
        return Inertia::render('Auth/ActivationPending', [
            'email' => $request->query('email'),
        ]);
    }

    /**
     * Muestra el formulario de reenvío.
     */
    public function showResend(Request $request): Response
    {
        return Inertia::render('Auth/ResendActivation', [
            'email' => $request->query('email', ''),
        ]);
    }

    /**
     * Reenvía el correo de activación.
     */
    public function resend(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return back()->with('error', 'No encontramos una cuenta con ese correo.');
        }

        if ($user->isActivated()) {
            return redirect()
                ->route('login')
                ->with('error', 'Esta cuenta ya está activada. Puedes iniciar sesión.');
        }

        // Regenerar el token
        $user->update([
            'activation_token' => Str::random(64),
            'activation_expires_at' => now()->addHours(48),
        ]);

        $this->sendActivationEmail($user);

        return redirect()
            ->route('activation.pending', ['email' => $user->email])
            ->with('success', 'Te enviamos un nuevo correo de activación.');
    }

    /**
     * Muestra el formulario de login.
     */
    public function showLogin(): Response
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Autentica al usuario.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Verificar si la cuenta está activada
        $user = User::where('email', $credentials['email'])->first();

        if ($user && !$user->isActivated() && $user->role === 'paciente') {
            return back()->withErrors([
                'email' => 'Tu cuenta aún no ha sido activada. Revisa tu correo o solicita un nuevo enlace.',
            ]);
        }

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Las credenciales no coinciden con nuestros registros.',
            ]);
        }

        $request->session()->regenerate();

        // Redirigir según el rol
        if (in_array(Auth::user()->role, ['admin', 'recepcionista'], true)) {
            return redirect()->intended('/admin');
        }

        return redirect()->intended('/mi-cuenta');
    }

    /**
     * Cierra la sesión.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /**
     * Envía el correo de activación.
     */
    protected function sendActivationEmail(User $user): void
    {
        $activationUrl = route('activation.activate', ['token' => $user->activation_token]);

        try {
            Mail::to($user->email)->send(new PatientActivationMail($user, $activationUrl));
        } catch (\Throwable $e) {
            report($e);
        }
    }
}