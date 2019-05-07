<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::namespace('Thinkstudeo\Rakshak\Http\Controllers')
    ->middleware(['auth', 'bindings'])
    // ->prefix(config('rakshak.route-prefix'))
    ->group(function () {
        Route::get('roles', 'RolesController@create')->name('rakshak.roles.create');
        Route::post('roles', 'RolesController@store')->name('rakshak.roles.store');
        Route::get('roles/{role}/edit', 'RolesController@edit')->name('rakshak.roles.edit');
        Route::patch('roles/{role}', 'RolesController@update')->name('rakshak.roles.update');
        Route::delete('roles/{role}', 'RolesController@destroy')->name('rakshak.roles.destroy');

        Route::post('abilities', 'AbilitiesController@store')->name('rakshak.abilities.store');
        Route::patch('abilities/{ability}', 'AbilitiesController@update')->name('rakshak.abilities.update');
        Route::delete('abilities/{ability}', 'AbilitiesController@destroy')->name('rakshak.abilities.destroy');
    });

Auth::routes();