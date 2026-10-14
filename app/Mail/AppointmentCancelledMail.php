<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentCancelledMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Appointment $appointment,
        public string $cancelledBy = 'clinic', // 'clinic' o 'patient'
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Tu cita ha sido cancelada - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment-cancelled',
            with: [
                'appointment' => $this->appointment,
                'reference' => 'CITA-' . str_pad($this->appointment->id, 6, '0', STR_PAD_LEFT),
                'cancelledBy' => $this->cancelledBy,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}