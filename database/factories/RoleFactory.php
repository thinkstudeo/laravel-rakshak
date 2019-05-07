<?php

use Faker\Generator as Faker;
use Thinkstudeo\Rakshak\Ability;

$factory->define(\Thinkstudeo\Rakshak\Role::class, function (Faker $faker) {
    return [
        'name'        => $faker->name,
        'label'       => ucfirst($faker->word),
        'description' => $faker->sentence,
        'active'      => true,
    ];
});

$factory->state(\Thinkstudeo\Rakshak\Role::class, 'withAbilities', [])
    ->afterCreatingState(\Thinkstudeo\Rakshak\Role::class, 'withAbilities', function ($role, $faker) {
        $ability1 = create(Ability::class);
        $ability2 = create(Ability::class);

        $role->addAbility($ability1);
        $role->addAbility($ability2);
    });
