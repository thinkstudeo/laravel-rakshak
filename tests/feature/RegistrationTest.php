<?php

namespace Thinkstudeo\Rakshak\Tests\Feature;

use App\User;
use Thinkstudeo\Rakshak\Tests\TestCase;
// use Thinkstudeo\Rakshak\Tests\Fixtures\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function visitor_can_visit_the_registration_page_and_see_the_registration_form()
    {
        $this->withoutExceptionHandling();
        $this->get('register')
            ->assertStatus(200)
            ->assertSee('Register')
            ->assertSee('E-Mail Address')
            ->assertSee('Mobile')
            ->assertSee('Password')
            ->assertSee('Confirm Password');
    }

    /** @test */
    public function visitor_can_register_via_the_registration_form()
    {
        Notification::fake();
        $this->assertCount(0, User::all());

        $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'username' => 'johndoe',
            'mobile' => '+919898989898',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertCount(1, User::all());
        $this->assertEquals('John Doe', User::first()->name);
        Notification::assertSentTo([User::first()], VerifyEmail::class);
    }
}
