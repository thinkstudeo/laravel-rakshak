<?php

namespace Thinkstudeo\Rakshak\Tests\Unit;

use Thinkstudeo\Rakshak\Role;
use Thinkstudeo\Rakshak\Ability;
use Thinkstudeo\Rakshak\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_add_an_ability_from_a_given_instance()
    {
        $ability = create(Ability::class);

        $role = create(Role::class);

        $this->assertCount(0, $role->abilities);

        $role->addAbility($ability);

        $this->assertCount(1, $role->fresh()->abilities);
    }

    /** @test */
    public function it_can_add_an_ability_from_a_given_name_string()
    {
        $ability = create(Ability::class);

        $role = create(Role::class);

        $this->assertCount(0, $role->abilities);

        $role->addAbility($ability->name);

        $this->assertCount(1, $role->fresh()->abilities);
    }

    /** @test */
    public function it_can_retract_an_ability_from_a_given_instance()
    {
        $ability = create(Ability::class);

        $role = create(Role::class);

        $role->addAbility($ability);
        $role = $role->fresh();

        $this->assertCount(1, $role->abilities);
        $this->assertEquals($ability->name, $role->abilities->first()->name);

        $role->retractAbility($ability);

        $this->assertCount(0, $role->fresh()->abilities);
    }

    /** @test */
    public function it_can_retract_an_ability_from_a_given_name_string()
    {
        $ability = create(Ability::class);

        $role = create(Role::class);

        $role->addAbility($ability);
        $role = $role->fresh();

        $this->assertCount(1, $role->abilities);
        $this->assertEquals($ability->name, $role->abilities->first()->name);

        $role->retractAbility($ability->name);

        $this->assertCount(0, $role->fresh()->abilities);
    }

    /** @test */
    public function it_can_verify_whether_a_given_ability_is_associated_with_itself()
    {
        $ability1 = create(Ability::class);
        $ability2 = create(Ability::class);

        $role = create(Role::class);

        $role->addAbility($ability1);
        $role->addAbility($ability2);
        $role = $role->fresh();

        $this->assertCount(2, $role->abilities);
        $this->assertTrue($role->hasAbility($ability1));
        $this->assertTrue($role->hasAbility($ability2->name));

        $role->retractAbility($ability1);
        $role = $role->fresh();

        $this->assertFalse($role->hasAbility($ability1));
        $this->assertTrue($role->hasAbility($ability2));
    }
}
