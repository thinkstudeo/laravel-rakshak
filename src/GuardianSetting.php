<?php

namespace Thinkstudeo\Guardian;

use Illuminate\Database\Eloquent\Model;
use Thinkstudeo\Guardian\Traits\UuidAsPrimaryKey;

class GuardianSetting extends Model
{
    use UuidAsPrimaryKey;

    /**
     * Set the incrementing property to false
     *
     * @var boolean
     */
    public $incrementing = false;

    /**
     * The primary key column type.
     *
     * @var string
     */
    protected $keyType = 'string';
    /**
     * Database table name for the model.
     *
     * @var string
     */
    protected $table = 'guardian_settings';

    /**
     * The attributes which are protected against mass assignment.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Attributes required to be cast to primitives.
     *
     * @var array
     */
    protected $casts = [
        'enable_2fa' => 'boolean'
    ];
}