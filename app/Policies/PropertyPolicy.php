<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PropertyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Owner, Admin, Staff can view properties list
        return $user->canAccessAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Property $property): bool
    {
        // Admin and Staff can view all properties
        if ($user->isAdmin() || $user->isStaff()) {
            return true;
        }

        // Owner can only view their own properties
        if ($user->isOwner()) {
            return $property->isOwnedBy($user->id);
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only Owner and Admin can create properties
        return $user->isOwner() || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Property $property): bool
    {
        // Admin can update all properties
        if ($user->isAdmin()) {
            return true;
        }

        // Owner can only update their own properties
        if ($user->isOwner()) {
            return $property->isOwnedBy($user->id);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Property $property): bool
    {
        // Admin can delete all properties
        if ($user->isAdmin()) {
            return true;
        }

        // Owner can only delete their own properties
        if ($user->isOwner()) {
            return $property->isOwnedBy($user->id);
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Property $property): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Property $property): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can publish the property.
     */
    public function publish(User $user, Property $property): bool
    {
        return $this->update($user, $property);
    }

    /**
     * Determine whether the user can feature the property.
     */
    public function feature(User $user, Property $property): bool
    {
        // Only Admin can feature properties
        return $user->isAdmin();
    }
}
