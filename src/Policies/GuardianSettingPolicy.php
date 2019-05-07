<?php

namespace Thinkstudeo\Guardian\Policies;

use App\User;
use Thinkstudeo\Guardian\GuardianSetting;
use Illuminate\Auth\Access\HandlesAuthorization;

class GuardianSettingPolicy
{
    use HandlesAuthorization;

    public function before($user, $setting)
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
        return false;
    }

    /**
     * Determine whether the user can view the role.
     *
     * @param  \App\User  $user
     * @param  \Thinkstudeo\Guardian\GuardianSetting  $setting
     * @return mixed
     */
    public function view(User $user, GuardianSetting $setting)
    {
        return false;
    }

    /**
     * Determine whether the user can create roles.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the role.
     *
     * @param  \App\User  $user
     * @param  \Thinkstudeo\Guardian\GuardianSetting  $setting
     * @return mixed
     */
    public function update(User $user, GuardianSetting $setting)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the role.
     *
     * @param  \App\User  $user
     * @param  \Thinkstudeo\Guardian\GuardianSetting  $setting
     * @return mixed
     */
    public function delete(User $user, GuardianSetting $setting)
    {
        return false;
    }
}
