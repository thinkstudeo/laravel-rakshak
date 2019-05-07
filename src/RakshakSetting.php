<?php

namespace Thinkstudeo\Rakshak;

use Illuminate\Database\Eloquent\Model;
use Thinkstudeo\Rakshak\Traits\UuidAsPrimaryKey;

class RakshakSetting extends Model
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
     * Database table name for the model.
     *
     * @var string
     */
    protected $table = 'rakshak_settings';

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
        'enable_2fa' => 'boolean',
    ];
}
