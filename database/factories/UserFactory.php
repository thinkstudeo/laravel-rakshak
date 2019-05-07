<?php

use Faker\Generator as Faker;

$factory->define(config('auth.providers.users.model'), function (Faker $faker) {
    $name = $faker->name;

    return [
        'name'           => $name,
        'username'       => strtolower(str_replace(' ', '', $name)),
        'email'          => $faker->unique()->safeEmail,
        'mobile'         => $faker->e164PhoneNumber,
        'password'       => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'status' => 'active',
    ];
});

$factory->state(config('auth.providers.users.model'), 'HrManager', ['name' => 'H R Manager'])
    ->afterCreatingState(config('auth.providers.users.model'), 'HrManager', function ($user, $faker) {
        $manageUsers = create(\Thinkstudeo\Rakshak\Ability::class, ['name' => 'manage_users']);

        $hrManager = create(\Thinkstudeo\Rakshak\Role::class, ['name' => 'hr_manager'])->addAbility($manageUsers);
        // dd($manageUsers->roles);
        $user->assignRole($hrManager);
    });

$factory->state(config('auth.providers.users.model'), 'ContentManager', ['name' => 'Content Manager'])
    ->afterCreatingState(config('auth.providers.users.model'), 'ContentManager', function ($user, $faker) {
        $manageContent = create(\Thinkstudeo\Rakshak\Ability::class, ['name' => 'manage_content']);

        $hrManager = create(\Thinkstudeo\Rakshak\Role::class, ['name' => 'content_manager'])->addAbility($manageContent);
        // dd($manageUsers->roles);
        $user->assignRole($hrManager);
    });

// $factory->state(config('auth.providers.users.model'), 'SuperUser', ['name' => 'Super User'])
//     ->afterCreatingState(config('auth.providers.users.model'), 'SuperUser', function ($user, $faker) {

//         $superUser = create(\Thinkstudeo\Rakshak\Role::class, ['name' => 'super']);
//         // dd($manageUsers->roles);
//         $user->assignRole($superUser);
//     });
