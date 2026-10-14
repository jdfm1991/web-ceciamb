<?php

namespace App\Observers;

use App\Mail\AppointmentCancelledMail;
use App\Mail\AppointmentConfirmedMail;
use App\Mail\NewAppointmentRequestMail;
use App\Models\Appointment;
use App\Models\ClinicInfo;
use Illuminate\Support\Facades\Mail;

class AppointmentObserver
{
    /**
     * Se dispara al crear una cita.
     */
    public function created(Appointment $appointment): void
    {
        // Notificar a recepción
        $recipient = ClinicInfo::getValue('email', config('mail.from.address'));

        try {
            Mail::to($recipient)->send(new NewAppointmentRequestMail($appointment));
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /**
     * Se dispara al actualizar una cita.
     */
    public function updated(Appointment $appointment): void
    {
        // Solo nos interesan los cambios de estado
        if (!$appointment->isDirty('status')) {
            return;
        }

        $oldStatus = $appointment->getOriginal('status');
        $newStatus = $appointment->status;

        match ($newStatus) {
            'confirmada' => $this->notifyConfirmed($appointment),
            'cancelada' => $this->notifyCancelled($appointment),
            default => null,
        };
    }

    /**
     * Notifica al paciente que su cita fue confirmada.
     */
    protected function notifyConfirmed(Appointment $appointment): void
    {
        if (empty($appointment->patient_email)) {
            return;
        }

        try {
            Mail::to($appointment->patient_email)->send(new AppointmentConfirmedMail($appointment));
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /**
     * Notifica al paciente que su cita fue cancelada.
     */
    protected function notifyCancelled(Appointment $appointment): void
    {
        if (empty($appointment->patient_email)) {
            return;
        }

        try {
            Mail::to($appointment->patient_email)->send(new AppointmentCancelledMail($appointment, 'clinic'));
        } catch (\Throwable $e) {
            report($e);
        }
    }
}