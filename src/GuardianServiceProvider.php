<?php

namespace Thinkstudeo\Guardian;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\AliasLoader;
use Thinkstudeo\Guardian\Console\InstallCommand;
use Thinkstudeo\Guardian\Console\PublishCommand;
use Thinkstudeo\Guardian\Middleware\VerifyTwoFactorOtp;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Thinkstudeo\Guardian\Middleware\CheckRole;
use Illuminate\Support\Facades\Blade;
use Thinkstudeo\Guardian\Support\BladeDirectives;

class GuardianServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Thinkstudeo\Guardian\Role::class         => \Thinkstudeo\Guardian\Policies\RolePolicy::class,
        \Thinkstudeo\Guardian\Ability::class      => \Thinkstudeo\Guardian\Policies\AbilityPolicy::class,
        \Thinkstudeo\Guardian\GuardianSetting::class => \Thinkstudeo\Guardian\Policies\GuardianSettingPolicy::class
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
        $this->mergeConfigFrom(__DIR__ . '/../config/guardian.php', 'guardian');

        $this->commands([
            InstallCommand::class,
            PublishCommand::class
        ]);

        $this->registerMiddleware();
    }

    /**
     * Merge the Guardian default config options.
     *
     * @return void
     */
    protected function mergeConfig(): void
    {
        if (!$this->app->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__ . '/../config/guardian.php', 'guardian');
        }
    }

    /**
     * Register the aliases for the package
     *
     * @return void
     */
    protected function registerAliases()
    {
        AliasLoader::getInstance()->alias('Guardian', Guardian::class);
    }

    /**
     * Register the package middlewares.
     *
     * @return void
     */
    protected function registerMiddleware()
    {
        Route::aliasMiddleware('guardian.2fa', VerifyTwoFactorOtp::class);
        Route::aliasMiddleware('role', CheckRole::class);
    }

    /**
     * Register the routes for the package.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Guardian::routes();
    }

    /**
     * Map the directory to load the views for the package.
     *
     * @return void
     */
    public function loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'guardian');
    }

    /**
     * Load migrations for the package.
     *
     * @return void
     */
    protected function loadMigrations()
    {
        if (env('APP_ENV') !== 'testing') {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
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
            Guardian::loadCache();
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
            __DIR__ . '/../resources/views' => resource_path('views/vendor/guardian'),
        ], 'views');

        $this->publishes([
            __DIR__ . '/../config/guardian.php' => config_path('guardian.php'),
        ], 'config');
    }
}