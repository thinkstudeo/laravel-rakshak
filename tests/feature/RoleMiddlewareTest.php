<?php

namespace Thinkstudeo\Rakshak\Tests\Feature;

use Illuminate\Support\Facades\Route;
use Thinkstudeo\Rakshak\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // $this->signInHrManager();
    }

    /** @test */
    public function guests_are_redirected_to_the_login_page()
    {
        Route::middleware('web', 'role:hr_manager')->any('/test', function () {
            return 'Ok';
        });

        $this->get('/test')->assertStatus(302);
    }

    /** @test */
    public function unauthorized_users_not_having_the_specified_role_are_forbidden()
    {
        // $this->withoutExceptionHandling();
        Route::middleware('web', 'role:hr_manager')->any('/test', function () {
            return 'Ok';
        });

        $this->signIn();
        $this->get('/test')->assertStatus(403);
    }

    /** @test */
    public function user_having_the_specified_role_is_allowed()
    {
        Route::middleware('web', 'role:hr_manager')->any('/test', function () {
            return 'Ok';
        });

        $this->signInHrManager();
        $this->get('/test')->assertStatus(200);
    }

    /** @test */
    public function user_having_any_of_the_specified_roles_is_allowed()
    {
        Route::middleware('web', 'role:hr_manager|content_manager|site_manager|super_user')->any('/test', function () {
            return 'Ok';
        });

        $this->signInHrManager();
        $this->get('/test')->assertStatus(200);
    }
}
