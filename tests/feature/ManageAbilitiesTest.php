<?php

namespace Thinkstudeo\Guardian\Tests\Feature;

use Thinkstudeo\Guardian\Ability;
use Thinkstudeo\Guardian\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageAbilitiesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->signInHrManager();
    }

    /** @test */
    public function guests_can_not_create_an_ability()
    {
        $this->signOut();

        $ability = make(Ability::class);
        $this->postJson(route('guardian.abilities.store'), $ability->toArray())
            ->assertStatus(401);
    }

    /** @test */
    public function unauthorized_users_can_not_create_an_ability()
    {
        $this->signIn();

        $ability = make(Ability::class);
        $this->postJson(route('guardian.abilities.store'), $ability->toArray())
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_view_the_form_to_create_a_new_ability()
    {
        $this->get(route('guardian.abilities.create'))
            ->assertStatus(200)
            ->assertViewIs('guardian::abilities.create');
    }

    /** @test */
    public function authorized_users_may_create_an_ability()
    {
        $ability = make(Ability::class);
        $this->postJson(route('guardian.abilities.store'), $ability->toArray())
            ->assertStatus(201);
    }

    /** @test */
    public function authorized_users_can_view_a_list_of_all_existing_abilities()
    {
        $ability = create(Ability::class, ['name' => 'Ability 1', 'description' => 'Description for Ability 1']);
        $this->get(route('guardian.abilities.index'))
            ->assertStatus(200)
            ->assertSee($ability->name)
            ->assertSee($ability->label)
            ->assertSee($ability->description);
    }

    /** @test */
    public function authorized_users_can_view_edit_ability_form()
    {
        $ability = create(Ability::class, ['name' => 'Ability 1', 'description' => 'Description for Ability 1']);
        $this->get($ability->path() . "/edit")
            ->assertStatus(200)
            ->assertViewIs('guardian::abilities.edit')
            ->assertSee($ability->name)
            ->assertSee($ability->label)
            ->assertSee($ability->description);
    }

    /** @test */
    public function authorized_users_may_update_any_ability()
    {
        $ability = create(Ability::class, ['name' => 'Ability 1', 'description' => 'Description for Ability 1']);
        $this->assertDatabaseHas('abilities', ['name' => 'Ability 1', 'description' => 'Description for Ability 1']);

        $ability->name        = 'Changed Name';
        $ability->description = 'Updated description';
        $this->patchJson(route('guardian.abilities.update', $ability->id), $ability->toArray())
            ->assertStatus(200);
        $this->assertDatabaseMissing('abilities', ['name' => 'Ability 1', 'description' => 'Description for Ability 1']);
        $this->assertDatabaseHas('abilities', ['name' => 'Changed Name', 'description' => 'Updated description']);
    }

    /** @test */
    public function authorized_users_may_delete_any_ability()
    {
        $ability = create(Ability::class, ['name' => 'Ability 1']);
        $this->assertDatabaseHas('abilities', ['name' => 'Ability 1']);

        $this->deleteJson(route('guardian.abilities.destroy', $ability->id))
            ->assertStatus(200);
        $this->assertDatabaseMissing('abilities', ['name' => 'Ability 1']);
    }

    /** @test */
    public function an_ability_can_not_be_created_without_a_name()
    {
        $ability = make(Ability::class, ['name' => null]);

        $this->postJson(route('guardian.abilities.store'), $ability->toArray())
            ->assertStatus(422)
            ->assertJsonValidationErrors('name');
    }

    /** @test */
    public function the_name_must_be_a_string()
    {
        $ability = make(Ability::class, ['name' => 1234]);

        $this->postJson(route('guardian.abilities.store'), $ability->toArray())
            ->assertStatus(422)
            ->assertJsonValidationErrors('name');
    }

    /** @test */
    public function duplicate_names_are_not_allowed()
    {
        create(Ability::class, ['name' => 'Ability 1']);

        $ability = make(Ability::class, ['name' => 'Ability 1']);

        $this->postJson(route('guardian.abilities.store'), $ability->toArray())
            ->assertStatus(422)
            ->assertJsonValidationErrors('name');
    }

    /** @test */
    public function the_name_must_be_atleast_3_characters()
    {
        $ability = make(Ability::class, ['name' => 'ab']);

        $this->postJson(route('guardian.abilities.store'), $ability->toArray())
            ->assertStatus(422)
            ->assertJsonValidationErrors('name');
    }

    /** @test */
    public function an_ability_can_not_be_created_without_a_description()
    {
        $ability = make(Ability::class, ['description' => null]);

        $this->postJson(route('guardian.abilities.store'), $ability->toArray())
            ->assertStatus(422)
            ->assertJsonValidationErrors('description');
    }

    /** @test */
    public function a_valid_string_description_is_required()
    {
        $ability = make(Ability::class, ['description' => 1234]);

        $this->postJson(route('guardian.abilities.store'), $ability->toArray())
            ->assertStatus(422)
            ->assertJsonValidationErrors('description');

        $ability = make(Ability::class, ['description' => 'A valid description']);

        $this->postJson(route('guardian.abilities.store'), $ability->toArray())
            ->assertSessionDoesntHaveErrors('description');
    }

    /** @test */
    public function a_unique_slug_is_automatically_created_when_creating_an_ability()
    {
        $ability = make(Ability::class, ['name' => 'Ability 1']);

        $this->postJson(route('guardian.abilities.store'), $ability->toArray());

        $this->assertDatabaseHas('abilities', ['name' => 'Ability 1']);
        $savedAbility = Ability::whereName($ability->name)->firstOrFail();
        $this->assertTrue($savedAbility->slug === 'ability-1');
    }
}