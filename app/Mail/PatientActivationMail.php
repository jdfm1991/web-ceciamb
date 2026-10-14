<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PatientActivationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $activationUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Activa tu cuenta - Hospital de Clínicas de Ceciamb',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.patient-activation',
            with: [
                'userName' => $this->user->name,
                'activationUrl' => $this->activationUrl,
                'expiresInHours' => 48,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}