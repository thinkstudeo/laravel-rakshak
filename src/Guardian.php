<?php

namespace Thinkstudeo\Guardian;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Thinkstudeo\Guardian\Tests\Fixtures\Notifications\TemporaryPasswordMail;

class Guardian
{
    /**
     * Register all routes for the package
     *
     * @return void
     */
    public static function routes()
    {
        $instance = new static;

        $instance->apiRoutes();
    }

    /**
     * Load the Guardian Settings values in Cache.
     *
     * @return void
     */
    public static function loadCache()
    {
        $settings = Schema::hasTable('guardian_settings') ? GuardianSetting::first() : (object)[
            'enable_2fa'        => config('guardian.enable_2fa'),
            'channel_2fa'       => 'email',
            'control_level_2fa' => 'admin'
        ];

        Cache::forever('guardian.enable_2fa', $settings->enable_2fa);
        Cache::forever('guardian.channel_2fa', $settings->channel_2fa);
        Cache::forever('guardian.control_level_2fa', $settings->control_level_2fa);
    }

    /**
     * Retister the api routes.
     *
     * @return void
     */
    public function apiRoutes()
    {
        Route::namespace('\Thinkstudeo\Guardian\Http\Controllers')
            ->middleware(['web', 'auth'])
            ->prefix(config('guardian.route_prefix'))
            ->group(function () {
                Route::get('login/2fa', 'TwoFactorController@showOtpForm')->name('guardian.2fa.show');
                Route::post('/ogin/2fa/verify', 'TwoFactorController@verifyOtp')->name('guardian.2fa.verify');

                Route::resource('roles', 'RolesController', ['as' => 'guardian']);

                Route::resource('abilities', 'AbilitiesController', ['as' => 'guardian']);

                Route::get('settings', 'GuardianSettingsController@edit')->name('guardian.settings.edit');
                Route::put('settings', 'GuardianSettingsController@update')->name('guardian.settings.update');
            });
    }

    /**
     * Generate a 6 digit random number to be sent as OTP.
     *
     * @return number
     */
    public static function generateOtp()
    {
        return mt_rand(100000, 999999);
    }

    /**
     * Send the generated OTP to the user via the configured channel.
     *
     * @param User $user
     * @return void
     */
    public static function sendOtp($user)
    {
        $channel      = Cache::get('guardian.channel_2fa');

        $notifications = config('guardian.login.' . $channel);

        foreach ($notifications as $notification) {
            return $user->notify(new $notification);
        }
    }

    /**
     * Send an email with instructions for temporary password
     * when a user is created by the admin.
     *
     * @param \Illuminate\Database\Eloquent\Model $user
     * @return void
     */
    public static function sendTemporaryPassword($user)
    {
        return $user->notify(new TemporaryPasswordMail);
    }

    /**
     * Get the path to the showVerifyOtpForm
     *
     * @return string
     */
    public static function verifyOtpPath()
    {
        return 'login/2fa';
    }
}