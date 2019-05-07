<?php

use Faker\Generator as Faker;

$factory->define(Thinkstudeo\Rakshak\Ability::class, function (Faker $faker) {
    return [
        'name'        => $faker->word(),
        'label'       => ucfirst($faker->word()),
        'description' => $faker->sentence(),
        'active'      => true
    ];
});
