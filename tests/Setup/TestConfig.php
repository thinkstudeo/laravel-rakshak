<?php

namespace Thinkstudeo\Rakshak\Tests\Setup;

class TestConfig
{
    public static function setup($app)
    {
        $app['config']->set('app.name', 'Thinkstudeo');
        $app['config']->set('session.lifetime', 120);
        $app['config']->set(
            'guardian.login',
            [
                'email'                      => ['Thinkstudeo\Rakshak\Tests\Fixtures\Notifications\SendLoginOtpMail'],
                'sms'                        => ['Thinkstudeo\Rakshak\Tests\Fixtures\Notifications\SendLoginOtpSms'],
                'verify_mobile'              => ['Thinkstudeo\Rakshak\Tests\Fixtures\Notifications\SendVerifyMobileOtpSms'],
                'otp_template'               => 'Your OTP for ' . config('app.name') . ' is 234567. It is valid for the next 10 minutes only.',
                'verify_mobile_sms_template' => '%s: Confirmation code to verify your mobile number is %s.'
            ]
        );
        $app['config']->set(
            'guardian.register',
            [
                'welcome_email'    => ['Thinkstudeo\Rakshak\Tests\Fixtures\Notifications\SendRegistrationWelcomeEmail'],
                'welcome_sms'      => ['Thinkstudeo\Rakshak\Tests\Fixtures\Notifications\SendRegistrationWelcomeSms'],
                'welcome_template' => 'Welcome %s! We are happy to have you onboard. Team ' . config('app.name'),
            ]
        );
    }
}
