<?php

namespace Thinkstudeo\Rakshak\Tests\Feature;

use Thinkstudeo\Rakshak\Role;
use Thinkstudeo\Rakshak\Ability;
use Thinkstudeo\Rakshak\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageRolesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->signInRakshak();
    }

    /** @test */
    public function guests_cannot_create_a_role()
    {
        // dd(test_base_path('app'));
        $this->signOut();
        $role = make(Role::class);

        $this->postJson(route('rakshak.roles.store'), $role->toArray())
            ->assertStatus(401);
    }

    /** @test */
    public function unauthorized_users_cannot_create_a_role()
    {
        $this->signOut();
        $this->signIn();
        $role = make(Role::class);

        $this->postJson(route('rakshak.roles.store'), $role->toArray())
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_view_the_form_to_create_a_new_role()
    {
        $this->get(route('rakshak.roles.create'))
            ->assertStatus(200)
            ->assertViewIs('rakshak::roles.create')
            ->assertViewHas('abilities');
    }

    /** @test */
    public function authorized_users_can_create_new_role()
    {
        $role = make(Role::class);

        $this->postJson(route('rakshak.roles.store'), $role->toArray())
            ->assertStatus(201);
        $this->assertDatabaseHas('roles', ['name' => $role->name]);
    }

    /** @test */
    public function authorized_users_can_view_a_list_of_all_existing_roles()
    {
        $role = create(Role::class, ['name' => 'Role 1', 'description' => 'Description for Role 1']);
        $this->get(route('rakshak.roles.index'))
            ->assertStatus(200)
            ->assertSee($role->name)
            ->assertSee($role->label)
            ->assertSee($role->description);
    }

    /** @test */
    public function authorized_users_can_create_new_role_with_abilities()
    {
        $role = make(Role::class);

        $ability1 = create(Ability::class, ['name' => 'ability_1']);
        $ability2 = create(Ability::class, ['name' => 'ability_2']);

        $data = array_merge($role->toArray(), [
            'abilities' => [$ability1->toArray(), $ability2->toArray()],
        ]);
        // dd($data);
        $this->postJson(route('rakshak.roles.store'), $data)
            ->assertStatus(201);

        $this->assertTrue($ability1->fresh()->roles->first()->name === $role->name);
        $this->assertTrue($ability2->fresh()->roles->first()->name === $role->name);
    }

    /** @test */
    public function authorized_users_can_view_the_form_to_edit_a_role()
    {
        $role = create(Role::class);
        $this->get(route('rakshak.roles.edit', $role->id))
            ->assertStatus(200)
            ->assertViewIs('rakshak::roles.edit')
            ->assertViewHasAll(['role', 'abilities'])
            ->assertStatus(200)
            ->assertSee($role->name)
            ->assertSee($role->label)
            ->assertSee($role->description);
    }

    /** @test */
    public function authorized_users_can_update_roles()
    {
        $role = create(Role::class);

        $role->name = 'Changed Name';
        $role->description = 'Updated description.';

        $this->patchJson(route('rakshak.roles.update', $role->id), $role->toArray())
            ->assertStatus(200)
            ->assertJsonFragment(['message' => "The role {$role->name} has been updated."]);

        $this->assertDatabaseHas('roles', ['id' => $role->id, 'name' => 'Changed Name']);
    }

    /** @test */
    public function authorized_users_can_update_abilities_associated_with_a_role()
    {
        $role = create(Role::class);
        $ability1 = create(Ability::class, ['name' => 'ability_1']);
        $data = array_merge($role->toArray(), ['abilities' => [$ability1->toArray()]]);

        $r = $this->patchJson(route('rakshak.roles.update', $role->id), $data)
            ->assertStatus(200);
        $this->assertCount(1, $role->fresh()->abilities);
        $this->assertTrue($role->fresh()->abilities->first()->name === $ability1->name);

        $ability2 = create(Ability::class);
        $data = array_merge($role->toArray(), ['abilities' => [$ability2->toArray()]]);

        $this->patchJson(route('rakshak.roles.update', $role->id), $data)->assertStatus(200);
        $this->assertCount(1, $role->fresh()->abilities);
        $this->assertTrue($role->fresh()->abilities->first()->name === $ability2->name);

        $data = array_merge($role->toArray(), ['abilities' => [$ability1->toArray(), $ability2->toArray()]]);

        $this->patchJson(route('rakshak.roles.update', $role->id), $data)->assertStatus(200);
        $this->assertCount(2, $role->fresh()->abilities);
        $this->assertTrue($role->fresh()->abilities->where('name', $ability1->name)->count() === 1);
        $this->assertTrue($role->fresh()->abilities->where('name', $ability2->name)->count() === 1);
    }

    /** @test */
    public function authorized_users_can_delete_a_role()
    {
        $role = create(Role::class, ['name' => 'sales_manager']);

        $this->assertDatabaseHas('roles', ['name' => 'sales_manager']);

        $this->deleteJson(route('rakshak.roles.destroy', $role->id))
            ->assertStatus(200);
        $this->assertDatabaseMissing('roles', ['name' => 'sales_manager']);
    }

    /** @test */
    public function a_role_cannot_be_created_without_a_name()
    {
        $role = make(Role::class, [
            'name' => null,
        ]);
        $this->postJson(route('rakshak.roles.store'), $role->toArray())
            ->assertStatus(422)
            ->assertJsonValidationErrors('name')
            ->assertJsonFragment(['name' => ['The name field is required.']]);
    }

    /** @test */
    public function it_requires_a_name_atleast_3_characters_long()
    {
        $role = make(Role::class, [
            'name' => 'ab',
        ]);

        $this->postJson(route('rakshak.roles.store'), $role->toArray())
            ->assertStatus(422)
            ->assertJsonValidationErrors('name')
            ->assertJsonFragment(['name' => ['The name must be at least 3 characters.']]);
    }

    /** @test */
    public function the_name_for_the_role_must_be_a_string()
    {
        $role = make(Role::class, [
            'name' => 1234,
        ]);

        $this->postJson(route('rakshak.roles.store'), $role->toArray())
            ->assertStatus(422)
            ->assertJsonValidationErrors('name')
            ->assertJsonFragment(['name' => ['The name must be a string.']]);
    }

    /** @test */
    public function it_does_not_allow_duplicate_names()
    {
        $role = create(Role::class, ['name' => 'sales_manager']);

        $newRole = make(Role::class, ['name' => 'sales_manager']);
        $this->postJson(route('rakshak.roles.store'), $newRole->toArray())
            ->assertStatus(422)
            ->assertJsonFragment(['name' => ['The name has already been taken.']]);
    }

    /** @test */
    public function it_requires_a_valid_name()
    {
        $this->withoutExceptionHandling();
        $role = make(Role::class, [
            'name' => 'Super User',
        ]);

        $this->postJson(route('rakshak.roles.store'), $role->toArray())
            ->assertStatus(201)
            ->assertJsonFragment(['message' => "New role {$role->name} has been created."]);
    }

    /** @test */
    public function it_cannot_be_created_without_a_description()
    {
        $role = make(Role::class, [
            'description' => null,
        ]);

        $this->postJson(route('rakshak.roles.store'), $role->toArray())
            ->assertStatus(422)
            ->assertJsonValidationErrors('description')
            ->assertJsonFragment(['description' => ['The description field is required.']]);
    }

    /** @test */
    public function the_description_must_be_a_string()
    {
        $role = make(Role::class, [
            'description' => 1234,
        ]);

        $this->postJson(route('rakshak.roles.store'), $role->toArray())
            ->assertStatus(422)
            ->assertJsonValidationErrors('description')
            ->assertJsonFragment(['description' => ['The description must be a string.']]);
    }

    /** @test */
    public function it_must_have_a_valid_description()
    {
        $role = make(Role::class, [
            'description' => 'A Valid description',
        ]);

        $this->postJson(route('rakshak.roles.store'), $role->toArray())
            ->assertStatus(201)
            ->assertJsonFragment(['message' => "New role {$role->name} has been created."]);
    }

    /** @test */
    public function it_automatically_creates_and_persists_a_slug_from_the_name()
    {
        $role = make(Role::class, ['name' => 'sales_manager']);

        $this->postJson(route('rakshak.roles.store'), $role->toArray())
            ->assertStatus(201);

        $role = Role::whereName($role->name)->firstOrFail();
        $this->assertEquals('sales-manager', $role->slug);
    }
}
