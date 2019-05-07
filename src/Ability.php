<?php

namespace Thinkstudeo\Rakshak;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Thinkstudeo\Rakshak\Traits\UuidAsPrimaryKey;

class Ability extends Model
{
    use UuidAsPrimaryKey;

    /**
     * Set the incrementing property to false.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The primary key column type.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The fields which are protected against mass assignment.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Set the slug value from the name attribute.
     *
     * @param string $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value, '-');
        $this->attributes['name'] = $value;
    }

    /**
     * Path to the ability.
     *
     * @return string
     */
    public function path()
    {
        return '/'.config('rakshak.route_prefix')."/abilities/{$this->{$this->getRouteKeyName()}}";
    }

    /**
     * Get all Roles associated with the Ability.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
