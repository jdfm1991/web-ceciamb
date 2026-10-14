<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentRescheduledMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Appointment $newAppointment,
        public Appointment $previousAppointment,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Tu cita ha sido reagendada - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment-rescheduled',
            with: [
                'newAppointment' => $this->newAppointment,
                'previousAppointment' => $this->previousAppointment,
                'newReference' => 'CITA-' . str_pad($this->newAppointment->id, 6, '0', STR_PAD_LEFT),
                'previousReference' => 'CITA-' . str_pad($this->previousAppointment->id, 6, '0', STR_PAD_LEFT),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}