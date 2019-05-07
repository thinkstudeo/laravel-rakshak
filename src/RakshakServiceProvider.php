<?php

namespace Thinkstudeo\Rakshak;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\AliasLoader;
use Thinkstudeo\Rakshak\Middleware\CheckRole;
use Thinkstudeo\Rakshak\Console\InstallCommand;
use Thinkstudeo\Rakshak\Console\PublishCommand;
use Thinkstudeo\Rakshak\Support\BladeDirectives;
use Thinkstudeo\Rakshak\Middleware\VerifyTwoFactorOtp;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class RakshakServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Thinkstudeo\Rakshak\Role::class         => \Thinkstudeo\Rakshak\Policies\RolePolicy::class,
        \Thinkstudeo\Rakshak\Ability::class      => \Thinkstudeo\Rakshak\Policies\AbilityPolicy::class,
        \Thinkstudeo\Rakshak\RakshakSetting::class => \Thinkstudeo\Rakshak\Policies\RakshakSettingPolicy::class,
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishes();
        }

        $this->mergeConfig();
        $this->registerAliases();
        $this->registerPolicies();
        $this->registerRoutes();
        $this->loadViews();
        $this->loadMigrations();
        $this->loadCache();
        BladeDirectives::register();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/rakshak.php', 'rakshak');

        $this->commands([
            InstallCommand::class,
            PublishCommand::class,
        ]);

        $this->registerMiddleware();
    }

    /**
     * Merge the Rakshak default config options.
     *
     * @return void
     */
    protected function mergeConfig(): void
    {
        if (! $this->app->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__.'/../config/rakshak.php', 'rakshak');
        }
    }

    /**
     * Register the aliases for the package.
     *
     * @return void
     */
    protected function registerAliases()
    {
        AliasLoader::getInstance()->alias('Rakshak', Rakshak::class);
    }

    /**
     * Register the package middlewares.
     *
     * @return void
     */
    protected function registerMiddleware()
    {
        Route::aliasMiddleware('rakshak.2fa', VerifyTwoFactorOtp::class);
        Route::aliasMiddleware('role', CheckRole::class);
    }

    /**
     * Register the routes for the package.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Rakshak::routes();
    }

    /**
     * Map the directory to load the views for the package.
     *
     * @return void
     */
    public function loadViews()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'rakshak');
    }

    /**
     * Load migrations for the package.
     *
     * @return void
     */
    protected function loadMigrations()
    {
        if (env('APP_ENV') !== 'testing') {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
    }

    /**
     * Load the cache keys for the package.
     *
     * @return void
     */
    protected function loadCache()
    {
        if (env('APP_ENV') !== 'testing') {
            Rakshak::loadCache();
        }
    }

    /**
     * Register the publishable config and views for the package.
     *
     * @return void
     */
    protected function registerPublishes()
    {
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/rakshak'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../config/rakshak.php' => config_path('rakshak.php'),
        ], 'config');
    }
}
