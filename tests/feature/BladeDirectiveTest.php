<?php

namespace Thinkstudeo\Rakshak\Tests\Feature;

use Illuminate\Support\Facades\Route;
use Thinkstudeo\Rakshak\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BladeDirectiveTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Route::get('test-blade', function () {
            return view('test');
        });
    }

    /** @test */
    public function it_can_hide_content_from_guests()
    {
        $this->withoutExceptionHandling();
        $this->get('/test-blade')
            ->assertSee('Not an H R Manager');
    }

    /** @test */
    public function it_can_hide_content_from_users_not_having_specified_role()
    {
        $this->signIn();
        $this->get('/test-blade')
            ->assertSee('Not an H R Manager');
    }

    /** @test */
    public function it_reveals_the_content_to_user_having_specified_role()
    {
        $this->signInHrManager();
        $this->get('/test-blade')
            ->assertSee("I'm an H R Manager")
            ->assertDontSee("I'm a Content Manager");

        $this->signInContentManager();
        $this->get('/test-blade')
            ->assertSee("I'm a Content Manager")
            ->assertDontSee("I'm an H R Manager");
    }

    /** @test */
    public function it_can_check_from_within_multiple_roles_to_see_if_user_has_any_one_role()
    {
        $this->withoutExceptionHandling();
        $this->signInHrManager();
        $this->get('/test-blade')
            ->assertSee("I'm an H R Manager")
            ->assertSee('Either H R Manager or Super User');
    }
}
