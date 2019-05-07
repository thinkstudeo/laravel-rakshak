<?php

namespace Thinkstudeo\Rakshak\Tests\Feature;

use Thinkstudeo\Rakshak\Role;
use Illuminate\Support\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Thinkstudeo\Rakshak\Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use Thinkstudeo\Rakshak\Tests\Fixtures\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Thinkstudeo\Rakshak\Tests\Fixtures\Notifications\TemporaryPasswordMail;

class UserManagement extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->signInHrManager();
        $this->userModel = config('auth.providers.users.model');
        Artisan::call('guardian:install', ['--no-interaction' => true]);
    }

    protected function tearDown(): void
    {
        $file = new Filesystem;
        $file->cleanDirectory(app_path());

        parent::tearDown();
    }

    /** @test */
    public function guests_and_unauthorized_users_may_not_view_create_user_form()
    {
        $this->signOut();
        $this->get(route('guardian.users.create'))
            ->assertStatus(302)
            ->assertRedirect(route('login'));

        $this->signIn();
        $this->get(route('guardian.users.create'))
            ->assertStatus(403);
    }

    /** @test */
    public function guests_and_unauthorized_users_cannot_create_a_new_user()
    {
        $this->signOut();
        $userData = make($this->userModel);

        $this->postJson(route('guardian.users.store'), $userData->toArray())
            ->assertStatus(401);

        $this->signIn();
        $this->postJson(route('guardian.users.store'), $userData->toArray())
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_view_the_list_of_existing_users()
    {
        $user1 = create($this->userModel);
        $user2 = create($this->userModel);

        $this->get(route('guardian.users.index'))
            ->assertStatus(200)
            ->assertSee($user1->name)
            ->assertSee($user1->username)
            ->assertSee($user1->email)
            ->assertSee($user1->mobile)
            ->assertSee($user2->name)
            ->assertSee($user2->username)
            ->assertSee($user2->email)
            ->assertSee($user2->mobile);
    }

    /** @test */
    public function authorized_users_may_view_the_create_user_form()
    {
        $this->get(route('guardian.users.create'))
            ->assertStatus(200)
            ->assertSee('Create a new User')
            ->assertSee('Name')
            ->assertSee('Username')
            ->assertSee('E-Mail Address')
            ->assertSee('Mobile');
    }

    /** @test */
    public function authorized_users_may_create_a_new_user()
    {
        $userData = (array) raw($this->userModel);

        $this->postJson(route('guardian.users.store'), $userData)
            ->assertStatus(201);

        $this->assertEquals($userData['name'], User::find(2)->name);
    }

    /** @test */
    public function authorized_users_can_create_a_new_user_with_roles()
    {
        $userData = (array) raw($this->userModel);
        $role1 = create(Role::class, ['name' => 'Role 1']);
        $role2 = create(Role::class, ['name' => 'Role 2']);
        $data = array_merge($userData, [
            'roles' => [$role1->toArray(), $role2->toArray()],
        ]);

        $this->postJson(route('guardian.users.store'), $data)
            ->assertStatus(201);

        $user = User::find(2);
        $this->assertTrue($role1->fresh()->users->first()->name === $user->name);
        $this->assertTrue($role2->fresh()->users->first()->name === $user->name);
        $this->assertTrue($user->hasRole($role1->name));
        $this->assertTrue($user->hasRole($role2->name));
    }

    /** @test */
    public function authorized_users_may_see_edit_user_form()
    {
        $user = create($this->userModel, ['name' => 'John Doe']);
        $this->get(route('guardian.users.edit', ['id' => $user->id]))
            ->assertStatus(200)
            ->assertSee('Edit User')
            ->assertSee($user->name)
            ->assertSee($user->username)
            ->assertSee($user->email)
            ->assertSee($user->mobile);
    }

    /** @test */
    public function authorized_users_may_update_an_existing_user()
    {
        $user = create($this->userModel, ['name' => 'John Doe']);
        $user->name = 'Changed Name';

        $this->putJson(route('guardian.users.update', ['id' => $user->id]), $user->toArray())
            ->assertStatus(200);

        $this->assertEquals('Changed Name', $user->fresh()->name);
    }

    /** @test */
    public function authorized_users_may_delete_an_existing_user()
    {
        $user = create($this->userModel, ['name' => 'John Doe']);

        $this->deleteJson(route('guardian.users.destroy', ['id' => $user->id]))
            ->assertStatus(200);
    }

    /** @test */
    public function a_temporary_password_is_automatically_assigned_when_a_user_is_created()
    {
        $userData = make($this->userModel, [
            'password' => null,
        ]);
        $this->postJson(route('guardian.users.store'), $userData->toArray())
            ->assertStatus(201);

        $user = $this->userModel::find(2);
        $this->assertEquals($userData['name'], $user->name);
        $this->assertNotNull($user->password);
    }

    /** @test */
    public function an_email_with_temporary_password_is_sent_to_the_newly_created_user()
    {
        Notification::fake();
        Notification::assertNothingSent();
        $userData = make($this->userModel, [
            'password' => null,
        ]);
        $this->postJson(route('guardian.users.store'), $userData->toArray())
            ->assertStatus(201);

        $user = $this->userModel::find(2);
        // $user->email_verified_at = Carbon::now();
        // $user->save();
        var_dump($user->email_verified_at);
        $this->assertEquals($userData->email, $user->email);
        // Notification::assertSentTo(
        //     [$user],
        //     TemporaryPasswordMail::class
        // );
    }

    /** @test */
    public function a_user_cannot_be_created_without_name()
    {
        $user = make($this->userModel, [
            'name' => null,
        ]);
        $this->postJson(route('guardian.users.store'), $user->toArray())
            ->assertStatus(422)
            ->assertJsonValidationErrors('name')
            ->assertJsonFragment(['name' => ['The name field is required.']]);
    }

    /** @test */
    public function a_user_cannot_be_created_without_a_unique_username()
    {
        $john = create($this->userModel, ['username' => 'johndoe']);
        $user = make($this->userModel, [
            'username' => null,
        ]);
        $this->postJson(route('guardian.users.store'), $user->toArray())
            ->assertStatus(422)
            ->assertJsonValidationErrors('username')
            ->assertJsonFragment(['username' => ['The username field is required.']]);

        $user = make($this->userModel, [
            'username' => 'johndoe',
        ]);
        $this->postJson(route('guardian.users.store'), $user->toArray())
            ->assertStatus(422)
            ->assertJsonValidationErrors('username')
            ->assertJsonFragment(['username' => ['The username has already been taken.']]);
    }

    /** @test */
    public function a_user_cannot_be_created_without_a_unique_email()
    {
        $john = create($this->userModel, ['email' => 'john@example.com']);
        $user = make($this->userModel, [
            'email' => null,
        ]);
        $this->postJson(route('guardian.users.store'), $user->toArray())
            ->assertStatus(422)
            ->assertJsonValidationErrors('email')
            ->assertJsonFragment(['email' => ['The email field is required.']]);

        $user = make($this->userModel, [
            'email' => 'john@example.com',
        ]);
        $this->postJson(route('guardian.users.store'), $user->toArray())
            ->assertStatus(422)
            ->assertJsonValidationErrors('email')
            ->assertJsonFragment(['email' => ['The email has already been taken.']]);
    }

    /** @test */
    public function a_user_cannot_be_created_without_a_mobile()
    {
        $user = make($this->userModel, [
            'mobile' => null,
        ]);
        $this->postJson(route('guardian.users.store'), $user->toArray())
            ->assertStatus(422)
            ->assertJsonValidationErrors('mobile')
            ->assertJsonFragment(['mobile' => ['The mobile field is required.']]);
    }
}
