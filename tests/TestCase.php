<?php

namespace Thinkstudeo\Guardian\Tests;

use Thinkstudeo\Guardian\Guardian;
use Thinkstudeo\Guardian\Tests\Setup\TestViews;
use Thinkstudeo\Guardian\Tests\Setup\TestConfig;
use Thinkstudeo\Guardian\Tests\Setup\TestRoutes;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrations();
        $this->withFactories(__DIR__ . '/../database/factories');
        // $this->loadCache();
        file_put_contents(app_path('User.php'), file_get_contents(guardian_test_path('stubs/User.stub')));
    }

    /**
     * Setup the environment for the tests.
     *
     * @param [type] $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // TestConfig::setup($app);
        TestViews::setup();
        TestRoutes::setup();
    }

    /**
     * Load migrations for the tests.
     *
     * @return void
     */
    protected function loadMigrations()
    {
        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    /**
     * Load the cache keys for the application for the tests.
     *
     * @return void
     */
    protected function loadCache()
    {
        Guardian::loadCache();
    }

    /**
     * Register the service providers for the tests
     *
     * @param [type] $app
     * @return void
     */
    protected function getPackageProviders($app)
    {
        return [
            \Orchestra\Database\ConsoleServiceProvider::class,
            \Thinkstudeo\Guardian\GuardianServiceProvider::class,
            \Thinkstudeo\Guardian\Tests\TestsServiceProvider::class
        ];
    }

    /**
     * Register the aliases for tests.
     *
     * @param [type] $app
     * @return void
     */
    protected function getPackageAliases($app)
    {
        return [
            'Guardian' => \Thinkstudeo\Guardian\Guardian::class,
        ];
    }

    /**
     * Login a user. If no user is provided, whip up a new user and log him in.
     *
     * @param User $user
     * @return void
     */
    public function signIn($user = null)
    {
        $user = $user ?: create(config('auth.providers.users.model'));

        $this->be($user);

        return $this;
    }

    /**
     * Logout the currently logged in user.
     *
     * @return void
     */
    public function signOut()
    {
        auth()->logout();

        return $this;
    }

    /**
     * Create a new user and assign the hr_manager role, then log in the user.
     *
     * @return void
     */
    public function signInHrManager()
    {
        $hrManager = factory(config('auth.providers.users.model'))->states('HrManager')->create();

        $this->actingAs($hrManager);
    }

    /**
     * Create a new user and assign the super role, then log in the user.
     *
     * @return void
     */
    public function signInSuperUser()
    {
        $superUser = factory(config('auth.providers.users.model'))->states('SuperUser')->create();

        $this->actingAs($superUser);
    }

    /**
     * Create a new user and assign the content_manager role, then log in the user.
     *
     * @return void
     */
    public function signInContentManager()
    {
        $contentManager = factory(config('auth.providers.users.model'))->states('ContentManager')->create();

        $this->actingAs($contentManager);
    }
}