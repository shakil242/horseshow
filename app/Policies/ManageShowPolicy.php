<?php

namespace App\Policies;

use App\User;
use App\ManageShows;
use Illuminate\Auth\Access\HandlesAuthorization;

class ManageShowPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the manage shows.
     *
     * @param  \App\User  $user
     * @param  \App\ManageShows  $manageShows
     * @return mixed
     */
    public function view(User $user, ManageShows $manageShows)
    {
        //
    }

    /**
     * Determine whether the user can create manage shows.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the manage shows.
     *
     * @param  \App\User  $user
     * @param  \App\ManageShows  $manageShows
     * @return mixed
     */
    public function update(User $user, ManageShows $manageShows)
    {
     return $user->id == $manageShows->user_id;
    }

    /**
     * Determine whether the user can delete the manage shows.
     *
     * @param  \App\User  $user
     * @param  \App\ManageShows  $manageShows
     * @return mixed
     */
    public function delete(User $user, ManageShows $manageShows)
    {
        //return $user->id == $manageShows->user_id;
    }
}
