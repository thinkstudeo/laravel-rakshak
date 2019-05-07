<?php

namespace Thinkstudeo\Rakshak\Tests;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Thinkstudeo\Rakshak\Rakshak;
use Illuminate\Support\Facades\Artisan;

class TestsServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Rakshak::routes();

        file_put_contents(app_path('Http/Controllers/Controller.php'), file_get_contents(__DIR__ . '/stubs/Controller.stub'));
    }
}
