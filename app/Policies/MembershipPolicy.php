<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Membership;
use Illuminate\Auth\Access\HandlesAuthorization;

class MembershipPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return ($user->hasRole(['admin', 'sales'])) ? true : false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Membership $membership
     * @return bool
     */
    public function view(User $user, Membership $membership): bool
    {
        return ($user->hasRole(['admin', 'sales'])) ? true : false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return ($user->hasRole(['admin', 'sales'])) ? true : false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Membership $membership
     * @return bool
     */
    public function update(User $user, Membership $membership): bool
    {
        return ($user->hasRole(['admin', 'sales'])) ? true : false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Membership $membership
     * @return bool
     */
    public function delete(User $user, Membership $membership): bool
    {
        return ($user->hasRole(['admin', 'sales'])) ? true : false;
    }

    /**
     * Determine whether the user can bulk delete.
     *
     * @param User $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return ($user->hasRole(['admin', 'sales'])) ? true : false;
    }

    /**
     * Determine whether the user can permanently delete.
     *
     * @param User $user
     * @param Membership $membership
     * @return bool
     */
    public function forceDelete(User $user, Membership $membership): bool
    {
        return ($user->hasRole(['admin', 'sales'])) ? true : false;
    }

    /**
     * Determine whether the user can permanently bulk delete.
     *
     * @param User $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return ($user->hasRole(['admin', 'sales'])) ? true : false;
    }

    /**
     * Determine whether the user can restore.
     *
     * @param User $user
     * @param Membership $membership
     * @return bool
     */
    public function restore(User $user, Membership $membership): bool
    {
        return ($user->hasRole(['admin', 'sales'])) ? true : false;
    }

    /**
     * Determine whether the user can bulk restore.
     *
     * @param User $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return ($user->hasRole(['admin', 'sales'])) ? true : false;
    }

    /**
     * Determine whether the user can replicate.
     *
     * @param User $user
     * @param Membership $membership
     * @return bool
     */
    public function replicate(User $user, Membership $membership): bool
    {
        return ($user->hasRole(['admin', 'sales'])) ? true : false;
    }

    /**
     * Determine whether the user can reorder.
     *
     * @param User $user
     * @return bool
     */
    public function reorder(User $user): bool
    {
        return ($user->hasRole(['admin', 'sales'])) ? true : false;
    }

}