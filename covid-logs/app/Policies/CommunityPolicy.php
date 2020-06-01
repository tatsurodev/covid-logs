<?php

namespace App\Policies;

use App\Community;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommunityPolicy
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
     * @param  \App\Community  $community
     * @return mixed
     */
    public function view(User $user, Community $community)
    {
        return $user->id == $community->user_id;
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
     * @param  \App\Community  $community
     * @return mixed
     */
    public function update(User $user, Community $community)
    {
        // $user->idと$community->user_idだとtypeが何故か違ってくるので===が使えない
        return $user->id == $community->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Community  $community
     * @return mixed
     */
    public function delete(User $user, Community $community)
    {
        return $user->id == $community->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Community  $community
     * @return mixed
     */
    public function restore(User $user, Community $community)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Community  $community
     * @return mixed
     */
    public function forceDelete(User $user, Community $community)
    {
        //
    }
}
