<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Route Prefix
    |--------------------------------------------------------------------------
    |
    | This value will be used as route prefix for all rakshak routes.
    |
    */
    'route_prefix' => 'rakshak',
    /*
    |--------------------------------------------------------------------------
    | Application's User Model
    |--------------------------------------------------------------------------
    |
    | Mainly to indicate the primary key type for the User model in your 
    | application - whether its the default bigIncrements or uuid.
    |
    */
    'users' => [
        'pk_type' => 'unsignedBigInteger'
    ],
    /*
    |--------------------------------------------------------------------------
    | Two Factor Authentication
    |--------------------------------------------------------------------------
    |
    | Use the below keys to configure the two factor authentication for app.
    | Switch to enable or disable two factor authentication - enable_2fa. 
    | Notification classes and sms templates for otp and welcome message.
    | Remember to use approved templates for sms messages in countries
    | where there are DND restrictions for transactional messaging.
    |
    */
    'enable_2fa' => false,
    'login'      => [
        'email'                      => ['App\Notifications\Rakshak\LoginOtpMail'],
        'sms'                        => ['App\Notifications\Rakshak\LoginOtpSms'],
        'verify_mobile'              => ['App\Notifications\Rakshak\VerifyMobileOtpSms'],
        'otp_template'               => 'Your OTP for ' . config('app.name') . ' is 234567. It is valid for the next 10 minutes only.',
        'verify_mobile_sms_template' => '%s: Confirmation code to verify your mobile number is %s.'
    ],
    'register' => [
        'welcome_email'    => ['App\Notifications\Rakshak\RegistrationWelcomeEmail'],
        'welcome_sms'      => ['App\Notifications\Rakshak\RegistrationWelcomeSms'],
        'welcome_template' => 'Welcome %s! We are happy to have you onboard. Team %s',
    ],
    /*
    |--------------------------------------------------------------------------
    | Authorisation Roles
    |--------------------------------------------------------------------------
    |
    | Define the name for the super user role.
    | Define the name for the authorizer user role.
    | User with authorizer roler can perform crud ops for Role & Ability
    |
    */
    'roles' => [
        'super_user' => 'super',
        'authorizer' => 'rakshak'
    ],
    /*
    |--------------------------------------------------------------------------
    | Sender
    |--------------------------------------------------------------------------
    |
    | Define the sender for email and sms messages.
    |
    */
    'sender' => [
        'email' => [
            'from' => env('RAKSHAK_EMAIL_FROM', config('mail.from.address')),
            'name' => env('RAKSHAK_EMAIL_FROM_NAME', config('mail.from.address'))
        ],
        'sms' => [
            'from' => env('TEXTLOCAL_TRANSACTIONAL_SENDER'),
            'number' => env('TEXTLOCAL_TRANSACTIONAL_FROM')
        ]
    ]
];
