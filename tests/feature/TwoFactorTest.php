<?php

namespace Thinkstudeo\Rakshak\Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Thinkstudeo\Rakshak\Tests\TestCase;
use App\Notifications\Rakshak\LoginOtpSms;
use App\Notifications\Rakshak\LoginOtpMail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TwoFactorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createDirectories();
        $this->makeRouteFile();
        Artisan::call('config:clear');
        Artisan::call('rakshak:install', ['--force' => true]);
    }

    private function createDirectories()
    {
        if (! is_dir($directory = base_path('routes'))) {
            mkdir($directory, 0755, true);
        }
        if (! is_dir($directory = app_path('Http/Controllers/Auth'))) {
            mkdir($directory, 0755, true);
        }
    }

    private function makeRouteFile()
    {
        $stub = __DIR__.'/../stubs/web.stub';

        file_put_contents(
            base_path('routes/web.php'),
            file_get_contents($stub)
        );
    }

    private function enable2fa()
    {
        config(['rakshak.enable_2fa' => true]);
        $this->userModel = config('auth.providers.users.model');
    }

    /** @test */
    public function two_factor_authentication_can_be_enabled()
    {
        $this->assertFalse(config('rakshak.enable_2fa'));

        config(['rakshak.enable_2fa' => true]);
        $this->assertTrue(config('rakshak.enable_2fa'));
    }

    /** @test */
    public function on_attempt_to_login_user_is_redirected_to_a_form_to_enter_otp()
    {
        $this->withoutExceptionHandling();
        //Arrange
        $this->enable2fa();
        Cache::put('rakshak.channel_2fa', 'email', 5);
        $user = create($this->userModel, ['email' => 'john@example.com', 'password' => bcrypt('secret')]);

        //Act
        $this->post('/login', ['email' => $user->email, 'password' => 'secret'])
            ->assertRedirect('/home');

        //Assert
        $this->get('/home')->assertRedirect('/login/2fa');
    }

    /** @test */
    public function when_email_is_set_as_2fa_channel_an_email_with_otp_is_sent_to_the_user()
    {
        $this->withoutExceptionHandling();
        //Arrange
        $this->enable2fa();
        Cache::put('rakshak.channel_2fa', 'email', 5);
        Notification::fake();
        Notification::assertNothingSent();
        $user = create($this->userModel, ['email' => 'john@example.com', 'password' => bcrypt('secret')]);

        //Act
        $this->post('/login', ['email' => $user->email, 'password' => 'secret'])
            ->assertRedirect('/home');
        $this->get('/home')->assertRedirect('/login/2fa');

        //Assert
        Notification::assertSentTo(
            [$user],
            LoginOtpMail::class,
            // $user,
            // SendLoginOtpMail::class,
            function ($notification, $channels) use ($user) {
                return $channels[0] === 'mail';
            }
        );
    }

    /** @test */
    public function when_sms_is_set_as_2fa_channel_an_sms_with_otp_is_sent_to_the_user()
    {
        //Arrange
        $this->enable2fa();
        Cache::put('rakshak.channel_2fa', 'sms', 5);
        Notification::fake();
        Notification::assertNothingSent();
        $user = create($this->userModel, ['email' => 'john@example.com', 'password' => bcrypt('secret'), 'mobile' => '+919033100026']);

        //Act
        $this->post('/login', ['email' => $user->email, 'password' => 'secret'])
            ->assertRedirect('/home');
        $this->get('/home')->assertRedirect('/login/2fa');

        //Assert

        Notification::assertSentTo(
            $user,
            LoginOtpSms::class,
            function ($notification, $channels) use ($user) {
                return $channels[0] === 'NotificationChannels\Textlocal\TextlocalChannel';
            }
        );
    }

    /** @test */
    public function once_the_user_submits_the_correct_opt_he_is_logged_in()
    {
        $this->withoutExceptionHandling();
        Notification::fake();
        //Arrange
        // $this->artisan('rakshak:install');
        $this->enable2fa();
        Cache::put('rakshak.channel_2fa', 'sms', 5);
        $user = create($this->userModel, ['email' => 'john@example.com', 'password' => bcrypt('secret')]);

        //Act
        $this->post('/login', ['email' => $user->email, 'password' => 'secret'])
            ->assertRedirect('/home');
        $this->get('/home')->assertRedirect('/login/2fa');
        // session(['intended' => '/home']);
        $this->assertTrue($user->fresh()->otp_expiry <= now());
        $this->post(route('rakshak.2fa.verify'), ['otp' => $user->fresh()->otp_token])->assertRedirect('/home');

        //Assert
        $this->assertTrue(auth()->user()->name === $user->name);
        $this->assertTrue(auth()->user()->otp_expiry > now());
    }
}
