<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::namespace('Thinkstudeo\Guardian\Http\Controllers')
    ->middleware(['auth', 'bindings'])
    // ->prefix(config('guardian.route-prefix'))
    ->group(function () {
        Route::get('roles', 'RolesController@create')->name('guardian.roles.create');
        Route::post('roles', 'RolesController@store')->name('guardian.roles.store');
        Route::get('roles/{role}/edit', 'RolesController@edit')->name('guardian.roles.edit');
        Route::patch('roles/{role}', 'RolesController@update')->name('guardian.roles.update');
        Route::delete('roles/{role}', 'RolesController@destroy')->name('guardian.roles.destroy');

        Route::post('abilities', 'AbilitiesController@store')->name('guardian.abilities.store');
        Route::patch('abilities/{ability}', 'AbilitiesController@update')->name('guardian.abilities.update');
        Route::delete('abilities/{ability}', 'AbilitiesController@destroy')->name('guardian.abilities.destroy');
    });

    Auth::routes();
