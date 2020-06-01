<?php

namespace App\Policies;

use App\Place;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlacePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Place  $place
     * @return mixed
     */
    public function view(User $user, Place $place)
    {
        return $user->id == $place->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Place  $place
     * @return mixed
     */
    public function update(User $user, Place $place)
    {
        return $user->id == $place->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Place  $place
     * @return mixed
     */
    public function delete(User $user, Place $place)
    {
        return $user->id == $place->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Place  $place
     * @return mixed
     */
    public function restore(User $user, Place $place)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Place  $place
     * @return mixed
     */
    public function forceDelete(User $user, Place $place)
    {
        //
    }
}
