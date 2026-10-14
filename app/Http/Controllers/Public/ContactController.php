<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Mail\ContactMessage;
use App\Models\ClinicInfo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Contact/Index');
    }

    public function store(StoreContactRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $recipient = ClinicInfo::getValue('email', config('mail.from.address'));

        try {
            Mail::to($recipient)->send(new ContactMessage(
                name: $validated['name'],
                email: $validated['email'],
                phone: $validated['phone'] ?? null,
                contactSubject: $validated['subject'],
                messageBody: $validated['message'],
            ));

            return redirect()
                ->route('contact.index')
                ->with('success', 'Tu mensaje fue enviado correctamente. Te responderemos a la brevedad.');
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'No pudimos enviar tu mensaje. Intenta nuevamente o contáctanos por teléfono.');
        }
    }
}
