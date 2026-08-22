<?php

namespace App\Policies;

use App\Models\Frontend\User;
use App\Models\Hosting;

class HostingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {

        return false;

    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Hosting $hosting): bool
    {

        return false;

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {

        return false;

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Hosting $hosting): bool
    {

        return false;

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Hosting $hosting): bool
    {

        return false;

    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Hosting $hosting): bool
    {

        return false;

    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Hosting $hosting): bool
    {

        return false;

    }
}
