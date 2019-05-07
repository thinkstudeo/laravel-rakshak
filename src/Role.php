<?php

namespace Thinkstudeo\Guardian;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Thinkstudeo\Guardian\Traits\UuidAsPrimaryKey;

class Role extends Model
{
    use UuidAsPrimaryKey;

    // protected $table = 'roles';

    // protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * The fields which are guarded against mass assigment.
     *
     * @var array
     */
    protected $guarded    = ['id'];

    /**
     * Set the slug attribute from the name attribute value.
     *
     * @param string $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->slug = Str::slug($value, '-');

        $this->attributes['name'] = $value;
    }

    /**
     * Path to the Role.
     *
     * @return string
     */
    public function path()
    {
        return "/" . config('guardian.route_prefix') . "/roles/{$this->{$this->getRouteKeyName()}}";
    }

    /**
     * Get all the Abilities associatex with the Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function abilities()
    {
        return $this->belongsToMany(Ability::class);
    }

    /**
     * Get all the Users associated with the Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(config('auth.providers.users.model'));
    }

    /**
     * Add the Ability provided as parameter to the given Role.
     *
     * @param \Thinkstudeo\Guardian\Ability|string $ability
     * @return \Thinkstudeo\Guardian\Role
     */
    public function addAbility($ability)
    {
        $model = $ability instanceof Ability ? $ability : Ability::whereName($ability)->firstOrFail();

        // $this->abilities()->attach($model->id);
        $this->abilities()->save($model);
        // dd($this->abilities);

        return $this;
    }

    /**
     * Remove the Ability provided as parameter from the given Role.
     *
     * @param string|\Thinkstudeo\Guardian\Ability $ability
     * @return \Thinkstudeo\Guardian\Role
     */
    public function retractAbility($ability)
    {
        $model = $ability instanceof Ability ? $ability : Ability::whereName($ability)->firstOrFail();

        $this->abilities()->detach($model->id);

        return $this;
    }

    /**
     * Determine whether the Ability is associated with the given Role.
     *
     * @param \Thinkstudeo\Guardian\Ability|string $ability
     * @return boolean
     */
    public function hasAbility($ability)
    {
        $model = $ability instanceof Ability ? $ability : Ability::whereName($ability)->firstOrFail();

        return !!$this->abilities()->whereName($model->name)->count();
    }
}
