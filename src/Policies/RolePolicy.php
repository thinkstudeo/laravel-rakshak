<?php

namespace Thinkstudeo\Guardian\Policies;

use App\User;
use Thinkstudeo\Guardian\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function before($user, $role)
    {
        if ($user->isSuperUser()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the listing of Abilities.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
        return $user->hasAbility('manage_users');
    }

    /**
     * Determine whether the user can view the role.
     *
     * @param  \App\User  $user
     * @param  \Thinkstudeo\Guardian\Role  $role
     * @return mixed
     */
    public function view(User $user, Role $role)
    {
        return $user->hasAbility('manage_users');
    }

    /**
     * Determine whether the user can create roles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasAbility('manage_users');
    }

    /**
     * Determine whether the user can update the role.
     *
     * @param  \App\User  $user
     * @param  \Thinkstudeo\Guardian\Role  $role
     * @return mixed
     */
    public function update(User $user, Role $role)
    {
        return $user->hasAbility('manage_users');
    }

    /**
     * Determine whether the user can delete the role.
     *
     * @param  \App\User  $user
     * @param  \Thinkstudeo\Guardian\Role  $role
     * @return mixed
     */
    public function delete(User $user, Role $role)
    {
        return $user->hasAbility('manage_users');
    }
}