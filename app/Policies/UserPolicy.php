<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the given profile.
     *
     * @param  \App\User  $user
     * @param  \App\User $profileUser
     * @return mixed
     */
    public function update(User $user, User $profileUser)
    {
        return $profileUser->id == $user->id;
    }
}
