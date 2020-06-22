<?php

namespace Thinkstudeo\Rakshak\Console;

use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    use DetectsApplicationNamespace;
    /**
     * The signature required to run the command from cli.
     *
     * @var string
     */
    protected $signature = 'rakshak:install
                    {--views : Only scaffold the authentication views}
                    {--force : Overwrite existing views by default}';

    /**
     * What the command does.
     *
     * @var string
     */
    protected $description = 'Install Rakshak package for Laravel';

    /**
     * Handle the execution of the command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Publishing Rakshak Resources and Assets...');
        $this->callSilent('rakshak:publish', ['--force' => $this->option('force') ? true : false]);
        $this->createDirectories();

        $this->comment('Making Auth Routes, Controllers and Views...');
        $this->exportRoutes();
        $this->makeControllers();
        $this->exportViews();

        $this->comment('Making Rakshak Notifications...');
        $this->exportNotifications();

        $this->comment('Updating the User model...');
        $this->updateUserModel();

        $this->info('Rakshak scaffolding installed successfully.');
    }

    /**
     * Copy the Authentication routes to the Laravel App.
     *
     * @return void
     */
    protected function exportRoutes()
    {
        if (
            ! Str::contains(file_get_contents(base_path('routes/web.php')), 'Auth::routes') ||
            $this->option('force')
        ) {
            file_put_contents(
                base_path('routes/web.php'),
                file_get_contents(__DIR__.'/stubs/routes.stub'),
                FILE_APPEND
            );
        }
    }

    /**
     * Controllers to be copied over to the main app.
     *
     * @var array
     */
    protected $controllers = [
        'RegisterController.stub' => 'Http/Controllers/Auth/RegisterController.php',
        'LoginController.stub'    => 'Http/Controllers/Auth/LoginController.php',
        'HomeController.stub'     => 'Http/Controllers/HomeController.php',
    ];

    /**
     * Copy the controllers to the main app.
     *
     * @return void
     */
    protected function makeControllers()
    {
        foreach ($this->controllers as $key => $value) {
            if (! file_exists(app_path($value)) || $this->option('force')) {
                file_put_contents(
                    app_path($value),
                    $this->compileStub(__DIR__."/stubs/controllers/{$key}")
                );
            }
        }
    }

    /**
     * Compiles the stub.
     *
     * @return string
     */
    protected function compileStub($stubPath)
    {
        return str_replace(
            '{{namespace}}',
            $this->getAppNamespace(),
            file_get_contents($stubPath)
        );
    }

    /**
     * Create the directories for the files.
     *
     * @return void
     */
    protected function createDirectories()
    {
        if (! is_dir($directory = resource_path('views/layouts'))) {
            mkdir($directory, 0755, true);
        }

        if (! is_dir($directory = resource_path('views/auth/passwords'))) {
            mkdir($directory, 0755, true);
        }

        if (! is_dir($directory = app_path('Notifications/Rakshak'))) {
            mkdir($directory, 0755, true);
        }
    }

    /**
     * The views that need to be exported.
     *
     * @var array
     */
    protected $views = [
        'auth/login.stub'           => 'auth/login.blade.php',
        'auth/register.stub'        => 'auth/register.blade.php',
        'auth/verify.stub'          => 'auth/verify.blade.php',
        'auth/passwords/email.stub' => 'auth/passwords/email.blade.php',
        'auth/passwords/reset.stub' => 'auth/passwords/reset.blade.php',
        'layouts/app.stub'          => 'layouts/app.blade.php',
        'home.stub'                 => 'home.blade.php',
    ];

    /**
     * Export the authentication views.
     *
     * @return void
     */
    protected function exportViews()
    {
        foreach ($this->views as $key => $value) {
            if (file_exists($view = resource_path('views/'.$value)) && ! $this->option('force')) {
                if (! $this->confirm("The [{$value}] view already exists. Do you want to replace it?")) {
                    continue;
                }
            }

            copy(
                __DIR__.'/stubs/views/'.$key,
                $view
            );
        }
    }

    /**
     * Copy the notifications to the main app, to allow users to customize the notifications.
     *
     * @return void
     */
    public function exportNotifications()
    {
        $stubs = (new Filesystem)->files(__DIR__.'/stubs/notifications/');

        foreach ($stubs as $stub) {
            $filename = str_replace('stub', 'php', basename($stub));
            $path = app_path("Notifications/Rakshak/{$filename}");

            if (! file_exists($path) || $this->option('force')) {
                file_put_contents(
                    $path,
                    $this->compileStub($stub->getPathname())
                );
            }
        }
    }

    /**
     * Replace the default User model.
     *
     * @return void
     */
    public function updateUserModel()
    {
        $stubPath = __DIR__.'/stubs/User.stub';
        $namespace = trim(str_replace(
            class_basename(config('auth.providers.users.model')),
            '',
            config('auth.providers.users.model')
        ), '\\');
        if (
            ! str_contains(file_get_contents(app_path('User.php')), 'HasRakshak') ||
            $this->option('force')
        ) {
            file_put_contents(
                app_path('User.php'),
                str_replace(
                    '{{namespace}}',
                    $namespace,
                    file_get_contents($stubPath)
                )
            );
        }
    }
}
