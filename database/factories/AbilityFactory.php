<?php

use Faker\Generator as Faker;

$factory->define(Thinkstudeo\Guardian\Ability::class, function (Faker $faker) {
    return [
        'name'        => $faker->word(),
        'label'       => ucfirst($faker->word()),
        'description' => $faker->sentence(),
        'active'      => true
    ];
});