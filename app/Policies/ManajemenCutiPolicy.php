<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ManajemenCuti;
use Illuminate\Auth\Access\HandlesAuthorization;

class ManajemenCutiPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_cuti');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ManajemenCuti $manajemenCuti): bool
    {
        return $user->can('view_cuti');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_cuti');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ManajemenCuti $manajemenCuti): bool
    {
        return $user->can('update_cuti');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ManajemenCuti $manajemenCuti): bool
    {
        return $user->can('delete_cuti');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_cuti');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, ManajemenCuti $manajemenCuti): bool
    {
        return $user->can('force_delete_cuti');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_cuti');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, ManajemenCuti $manajemenCuti): bool
    {
        return $user->can('restore_cuti');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_cuti');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, ManajemenCuti $manajemenCuti): bool
    {
        return $user->can('replicate_cuti');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_cuti');
    }
}
