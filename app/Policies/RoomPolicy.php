<?php

namespace App\Policies;

use App\Models\Room;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RoomPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admin, Staff, Owner can view rooms list
        return $user->isAdmin() || $user->isStaff() || $user->isOwner();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Room $room): bool
    {
        // Admin and Staff can view all rooms
        if ($user->isAdmin() || $user->isStaff()) {
            return true;
        }

        // Owner can only view rooms of their own properties
        if ($user->isOwner()) {
            return $room->property->isOwnedBy($user);
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only Admin and Owner can create rooms
        return $user->isAdmin() || $user->isOwner();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Room $room): bool
    {
        // Admin can update all rooms
        if ($user->isAdmin()) {
            return true;
        }

        // Owner can only update rooms of their own properties
        if ($user->isOwner()) {
            return $room->property->isOwnedBy($user);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Room $room): bool
    {
        // Admin can delete all rooms
        if ($user->isAdmin()) {
            return true;
        }

        // Owner can only delete rooms of their own properties
        if ($user->isOwner()) {
            return $room->property->isOwnedBy($user);
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Room $room): bool
    {
        // Same as delete permission
        return $this->delete($user, $room);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Room $room): bool
    {
        // Only Admin can force delete
        return $user->isAdmin();
    }
}
