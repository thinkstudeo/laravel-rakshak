<?php

namespace Thinkstudeo\Rakshak\Support;

use Illuminate\Support\Facades\Blade;

class BladeDirectives
{

    /**
     * Register the blade directives.
     *
     * @return void
     */
    public static function register()
    {
        Blade::if('role', function ($role) {
            return auth()->check() && auth()->user()->hasRole($role);
        });

        Blade::if('anyrole', function ($roles) {
            return auth()->check() && auth()->user()->hasAnyRole(explode('|', $roles));
        });
    }
}
