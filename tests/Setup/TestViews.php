<?php

namespace Thinkstudeo\Guardian\Tests\Setup;

use Illuminate\Support\Facades\View;

class TestViews
{
    public static function setup()
    {
        // $app['config']->set('view.paths', [__DIR__ . '/views']);
        View::addLocation(guardian_test_path('views'));
    }
}
