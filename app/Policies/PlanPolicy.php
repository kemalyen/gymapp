<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Membership;
use App\Models\Plan;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlanPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return ($user->role(['admin'])) ? true : false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Plan  plan
     * @return bool
     */
    public function view(User $user, Plan $plan): bool
    {
        return ($user->role(['admin'])) ? true : false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return ($user->role(['admin'])) ? true : false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Membership  $membership
     * @return bool
     */
    public function update(User $user, Membership $membership): bool
    {
        return ($user->role(['admin'])) ? true : false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Membership  $membership
     * @return bool
     */
    public function delete(User $user, Membership $membership): bool
    {
        return ($user->role(['admin'])) ? true : false;
    }

    /**
     * Determine whether the user can bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return ($user->role(['admin'])) ? true : false;
    }

    /**
     * Determine whether the user can permanently delete.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Membership  $membership
     * @return bool
     */
    public function forceDelete(User $user, Membership $membership): bool
    {
        return ($user->role(['admin'])) ? true : false;
    }

    /**
     * Determine whether the user can permanently bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return ($user->role(['admin'])) ? true : false;
    }

    /**
     * Determine whether the user can restore.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Membership  $membership
     * @return bool
     */
    public function restore(User $user, Membership $membership): bool
    {
        return ($user->role(['admin'])) ? true : false;
    }

    /**
     * Determine whether the user can bulk restore.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return ($user->role(['admin'])) ? true : false;
    }

    /**
     * Determine whether the user can replicate.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Membership  $membership
     * @return bool
     */
    public function replicate(User $user, Membership $membership): bool
    {
        return ($user->role(['admin'])) ? true : false;
    }

    /**
     * Determine whether the user can reorder.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function reorder(User $user): bool
    {
        return ($user->role(['admin'])) ? true : false;
    }

}