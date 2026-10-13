<?php

namespace App\Policies;

use App\Models\Specialty;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SpecialtyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'recepcionista'], true);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Specialty $specialty): bool
    {
        return in_array($user->role, ['admin', 'recepcionista'], true);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Specialty $specialty): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Specialty $specialty): bool
    {
        return $user->isAdmin();
    }
}
