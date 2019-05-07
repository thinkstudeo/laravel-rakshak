<?php

namespace Thinkstudeo\Guardian\Traits;

use Illuminate\Support\Str;

trait UuidAsPrimaryKey
{
    /**
     * Generate a uuid and insert it as primary key for the model being created.
     * Set the $incrementing to false to use the uuid primary key.
     *
     * @return void
     */
    public static function bootUuidAsPrimaryKey(): void
    {
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Str::orderedUuid()->toString();
        });
    }
}