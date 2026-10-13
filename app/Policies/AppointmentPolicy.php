<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    /**
     * Admin y recepcionista pueden ver el listado.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'recepcionista'], true);
    }

    /**
     * Admin y recepcionista pueden ver una cita.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        return in_array($user->role, ['admin', 'recepcionista'], true);
    }

    /**
     * No se permite crear citas desde el panel.
     * Las citas se crean desde el portal público o por reagendamiento.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Admin y recepcionista pueden editar (solo notas internas).
     */
    public function update(User $user, Appointment $appointment): bool
    {
        return in_array($user->role, ['admin', 'recepcionista'], true);
    }

    /**
     * Solo admin puede eliminar citas pendientes.
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        return $user->isAdmin() && $appointment->status === 'pendiente';
    }

    /**
     * Solo admin puede eliminar en masa.
     */
    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();
    }
}