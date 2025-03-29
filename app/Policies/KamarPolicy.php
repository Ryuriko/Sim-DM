<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Kamar;
use Illuminate\Auth\Access\HandlesAuthorization;

class KamarPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_kamar');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Kamar $kamar): bool
    {
        return $user->can('view_kamar');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_kamar');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Kamar $kamar): bool
    {
        return $user->can('update_kamar');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Kamar $kamar): bool
    {
        return $user->can('delete_kamar');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_kamar');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Kamar $kamar): bool
    {
        return $user->can('force_delete_kamar');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_kamar');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Kamar $kamar): bool
    {
        return $user->can('restore_kamar');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_kamar');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Kamar $kamar): bool
    {
        return $user->can('replicate_kamar');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_kamar');
    }
}
