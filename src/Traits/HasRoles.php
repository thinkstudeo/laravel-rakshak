<?php

namespace Thinkstudeo\Guardian\Traits;

use Thinkstudeo\Guardian\Role;
use Thinkstudeo\Guardian\Ability;

trait HasRoles
{
    /**
     * Get all the roles assigned to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Assign a Role to the current User.
     * 
     * @param $role
     * @return $this
     */
    public function assignRole($role)
    {
        $model = $role instanceof \Thinkstudeo\Guardian\Role ? $role : Role::whereName($role)->firstOrFail();

        $this->roles()->save($model);

        return $this;
    }

    /**
     * Retract a Role to the current User.
     * 
     * @param $role
     * @return $this
     */
    public function retractRole($role)
    {
        $model = $role instanceof \Thinkstudeo\Guardian\Role ? $role : Role::whereName($role)->firstOrFail();

        $this->roles()->detach($model->id);

        return $this;
    }

    /**
     * Determine if the user has the given role.
     * 
     * @param $role
     * @return bool
     */
    public function hasRole($role)
    {
        return is_string($role)
            ? !!$this->roles->contains('name', $role)
            : !!$this->roles->intersect([$role])->count();
    }

    /**
     * Determine if the user has any of the given roles.
     *
     * @param array $roles
     * @return boolean
     */
    public function hasAnyRole(array $roles)
    {
        return is_string($roles[0])
            ? !!$this->roles->intersect(Role::whereIn('name', $roles)->get())->count()
            : !!$this->roles->intersect($roles)->count();
    }

    /**
     * Determine if the user has any of the given abilities.
     * 
     * @param $task
     * @return bool
     */
    public function hasAbility($task)
    {
        $ability = is_string($task) ? Ability::whereName($task)->first() : $task;

        return $ability ? !!$ability->roles->intersect($this->roles)->count() : false;
    }

    /**
     * Determine if the user has any of the given abilities.
     *
     * @param array $abilities
     * @return boolean
     */
    public function hasAnyAbility(array $tasks)
    {
        $count = 0;
        foreach ($tasks as $task) {
            $ability = is_string($task) ? Ability::whereName($task)->first() : $task;

            $count += $ability ? $ability->roles->intersect($this->roles)->count() : 0;
        }
        return !!$count;
    }

    /**
     * Determine if the user is the super user.
     * 
     * @return bool
     */
    public function isSuperUser()
    {
        return !!$this->hasRole('super');
    }
}