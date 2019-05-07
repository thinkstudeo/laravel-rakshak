<?php

namespace Thinkstudeo\Rakshak\Tests\Setup;

use Illuminate\Support\Facades\Route;

class TestRoutes
{
    public static function setup()
    {
        Route::middleware('web')->group(function () {
            Route::get('register', "App\Http\Controllers\Auth\RegisterController@showRegistrationForm");
            Route::post('register', "App\Http\Controllers\Auth\RegisterController@register")->name('register');
            Route::get('login', "App\Http\Controllers\Auth\LoginController@showLoginForm")->name('login');
            Route::post('login', "App\Http\Controllers\Auth\LoginController@login");
            Route::post('logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');

            Route::get('/home', function () {
                return 'This is home page';
            })->middleware('auth', 'rakshak.2fa');
        });
    }
}
