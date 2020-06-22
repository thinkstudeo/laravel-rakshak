# Rakshak - Two Factor Authentication & Authorization for Laravel 5.7+

[![Latest Version on Packagist](https://img.shields.io/packagist/v/thinkstudeo/laravel-rakshak.svg?style=flat-square)](https://packagist.org/packages/thinkstudeo/laravel-rakshak)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/thinkstudeo/laravel-rakshak/master.svg?style=flat-square)](https://travis-ci.org/thinkstudeo/laravel-rakshak)
[![StyleCI](https://styleci.io/repos/185467188/shield)](https://styleci.io/repos/185467188)
[![Quality Score](https://img.shields.io/scrutinizer/g/thinkstudeo/laravel-rakshak.svg?style=flat-square)](https://scrutinizer-ci.com/g/thinkstudeo/laravel-rakshak)
[![SymfonyInsight](https://insight.symfony.com/projects/1e9ac532-ca35-4249-8800-2e23b706ced7/mini.svg)](https://insight.symfony.com/projects/1e9ac532-ca35-4249-8800-2e23b706ced7)
[![Total Downloads](https://img.shields.io/packagist/dt/thinkstudeo/laravel-rakshak.svg?style=flat-square)](https://packagist.org/packages/thinkstudeo/laravel-rakshak)

This package extends the authentication and authorization in a Lavarel application. 
- Roles and Abilities for granular authorization. 
- Two Factor Authentication functionality.
- Auth views, Blade directives and Route middleware.


## Contents

- [Rakshak - Two Factor Authentication & Authorization for Laravel 5.7+](#rakshak---two-factor-authentication--authorization-for-laravel-57)
  - [Contents](#contents)
  - [Installation](#installation)
  - [Config](#config)
  - [Usage](#usage)
    - [Two Factor Authentication](#two-factor-authentication)
    - [HasRakshak trait](#hasrakshak-trait)
      - [Adding and Retracting Ability to a Role](#adding-and-retracting-ability-to-a-role)
      - [Assigning and Retracting Role to User](#assigning-and-retracting-role-to-user)
      - [Check if the User has role](#check-if-the-user-has-role)
      - [Check if the User has any of given multiple role](#check-if-the-user-has-any-of-given-multiple-role)
      - [Check if the User has ability](#check-if-the-user-has-ability)
      - [Check if the User has any of given multiple abilities](#check-if-the-user-has-any-of-given-multiple-abilities)
    - [Roles and Abilities](#roles-and-abilities)
    - [Route Middleware](#route-middleware)
    - [Blade Directives](#blade-directives)
  - [Changelog](#changelog)
  - [Testing](#testing)
  - [Security](#security)
  - [Contributing](#contributing)
  - [Credits](#credits)
  - [License](#license)



## Installation

```bash
$ composer require thinkstudeo/laravel-rakshak
```
Then use the artisan command to install/integrate the package with your Laravel application.
```bash
$ php artisan rakshak:install
```
The artisan command will:
- register the authentication routes
- provide the authentication views (very similar to the `auth:make` command)
- publish the views for managing crud for **Roles, Abilities and Rakshak settings**
- register two middlewares
  - `rakshak.2fa` to verify otp, providing 2fa protection for routes
  - `role` to provide authorization protection for routes
- publish the package config `rakshak.php` to the `config` folder

Finally migrate the database
``bash
$ php artisan migrate
``
Migration will alter the `users` table to add 
- username
- mobile
- mobile_verified_at
- enable_2fa
- otp_token
- otp_expiry
- otp_channel
- status (for admin to activate/deactivate user)

Migration will add two roles defined in the `roles` key of the config file.
Only the User having role `config('rakshak.roles.super_user')` can access the `/rakshak/settings` route to view and edit the Two Factor settings.
User having either `config('rakshak.roles.super_user')` or `config('rakshak.roles.authorizer')`

**It is recommended to install the package in a fresh Laravel application**



## Config

Package config file is published at `config/rakshak.php`.

It allows to enable ***Two Factor Authentication*** - by default it is not enabled. It also allows to replace/modify the notification used for sending the otp and welcome message on registration.

The default notifications are published in the `app/Notifications/Rakshak` directory. You may modify or replace the notifications and then replace the corresponding config key with the FQCN of your new notification.

```php
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
```
You can use any of the [Laravel Notification channels](http://laravel-notification-channels.com/) for notifications. By default the package includes [Textlocal Notification Channel](https://packagist.org/packages/thinkstudeo/textlocal-notification-channel) for sms messaging.

## Usage

The package provides the option to enable **Two Factor Authentication** as also the level of control i.e. once an administrator enables the `2fa` module it can be applied to all users by the admin or each user can be given the option to enable `2fa` for own use.

### Two Factor Authentication
Route: `/rakshak/settings` is also provided for if you want to provide an easy way for other/client admins to control the Two Factor Authentication.

![Rakshak Settings](/docs/guardian-settings.png)

If you want to provide user level control for enabling/disabling Two Factor Authentication, you will need to  implement:
- Route and Navigation for user to access the settings area
- Settings view to:
  -  enable/disable `2fa`, select channel `sms` or default `email`
  -  check whether mobile (number) is verified if `sms` channel is selected
  -  Button/link to send otp for mobile (number) verification

### HasRakshak trait

The `User.php` file  - as per the `config('auth.providers.users.model')` is updated to use the `HasRakshak` trait. The trait provides a number of functions and `roles` relation to the `User`.

```php
$user = User::first();
$role = Role::whereName('hr_manager')->first();
$ability = Ability::whereName('manage_users')->first();
```

#### Adding and Retracting Ability to a Role
```php
// You can add an existing ability to a role
//By passing the Ability model instance
$role->addAbility($ability);

//Or by passing an Ability name string
$role->addAbility('manage_users');

//To retract an ability form a role
//By passing the Ability name string
$role->retractAbility('manage_users');

//Or - by passing the model instance
$role->retractAbility($ability);
```

#### Assigning and Retracting Role to User
```php
//By passing the Role model instance
$user->assignRole($role);

//Or - by passing the Role name string

$user->assignRole('hr_manager');

//Retract a role from the user
//By passing the Role model instance
$user->retractRole($role);

//Or by passing the Role name string
$user->retractRole('hr_manager');
```

#### Check if the User has role

```php
//Passing the Role model instance
$user->hasRole($role);

//Passing the Role name string
$user->hasRole('hr_manager');
```

#### Check if the User has any of given multiple role

```php
//Passing an array of multiple Role model instances
$user->hasAnyRole([$role, $role2]);

//Passing array of multiple Role name strings
$user->hasAnyRole(['hr_manager', 'content_manager']);
```


#### Check if the User has ability

```php
//Passing the Ability model instance
$user->hasAbility($ability);

//Passing the Ability name string
$user->hasAbility('manage_users');
```

#### Check if the User has any of given multiple abilities

```php
//Passing an array of multiple Ability model instances
$user->hasAnyAbility([$ability, $ability2]);

//Passing array of multiple Ability name strings
$user->hasAnyAbility(['manage_users', 'manage_content']);
```

### Roles and Abilities
Route: `/rakshak/roles` will provide a list of all existing roles

![Roles Listing](/docs/roles-index.png)

Route: `/rakshak/roles/{role}/edit` will provide the edit role form

![Roles Edit](/docs/role-edit.png)

Route: `/rakshak/roles/create` will provide the create new role form

![Roles Create](/docs/role-create.png)

Similarly the corresponding routes for **Abilities** are also provided for easy crud operations.

All package views are published to `resources/views/vendor/rakshak` directory.

### Route Middleware
A route middleware `role` is registered by the package. 

```php
//Protect the route and make it accessible only to users having hr_manager role.
Route::get('/some-route', 'SomeController@action')->middleware('role:hr_manager');

//Protect the route and make it accessible only to users having either of hr_manager, content_manager or super role.
Route::get('/some-route', 'SomeController@action')->middleware('role:hr_manager|content_manager|super');
```

To protect the routes with valid opt, use the `rakshak.2fa` middleware.

Once Two Factor Authentication is enabled, and `rakshak.2fa` middleware is applied to any route,
it will check for a valid otp associated with the user.

Once the generated otp is sent to the user and user verifies the login with otp it will stay verified till session lifetime.
```php
//Protect the route to check for valid otp token.
Route::post('/another-route', 'AnotherController@action')->middleware('rakshak.2fa');
```

### Blade Directives

```php
@role('hr_manager')
    User has the role of hr_manager
@elserole('super')
    User has the role of super
@else
    User does not have the role of hr_manager or super
@endrole
```

If you want to check against multiple roles, there's another directive where you can check against multiple roles separated by `|`
```php
@anyrole('hr_manager|super')
    Visible to user having role of hr_manager or super
@else
    User does not have the role of hr_manager or super
@endanyrole
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

```bash
$ composer test
```

## Security

If you discover any security related issues, please email neerav@thinkstudeo.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

-   [Neerav Pandya](https://github.com/neeravp)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
