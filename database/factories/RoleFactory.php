<?php

use Faker\Generator as Faker;
use Thinkstudeo\Guardian\Ability;

$factory->define(\Thinkstudeo\Guardian\Role::class, function (Faker $faker) {
    return [
        'name'        => $faker->name,
        'label'       => ucfirst($faker->word),
        'description' => $faker->sentence,
        'active'      => true
    ];
});

$factory->state(\Thinkstudeo\Guardian\Role::class, 'withAbilities', [])
    ->afterCreatingState(\Thinkstudeo\Guardian\Role::class, 'withAbilities', function ($role, $faker) {
        $ability1 = create(Ability::class);
        $ability2 = create(Ability::class);

        $role->addAbility($ability1);
        $role->addAbility($ability2);
    });
