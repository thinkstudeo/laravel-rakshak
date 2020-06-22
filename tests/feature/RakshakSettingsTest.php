<?php

namespace Thinkstudeo\Rakshak\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Thinkstudeo\Rakshak\Rakshak;
use Thinkstudeo\Rakshak\RakshakSetting;
use Thinkstudeo\Rakshak\Tests\TestCase;

class RakshakSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->signInSuperUser();
    }

    /** @test */
    public function guests_and_unauthorized_users_may_not_view_the_rakshak_settings_edit_form()
    {
        // $this->withoutExceptionHandling();
        $this->signOut();
        $this->get(route('rakshak.settings.edit'))
            ->assertStatus(302);

        $this->signIn();
        $this->get(route('rakshak.settings.edit'))
            ->assertStatus(403);
    }

    /** @test */
    public function guests_and_unauthorized_users_may_not_update_the_rakshak_settings()
    {
        $settings = RakshakSetting::first();
        $this->signOut();
        $this->put(route('rakshak.settings.update'), $settings->toArray())
            ->assertStatus(302);

        $this->signIn();

        $this->put(route('rakshak.settings.update'), $settings->toArray())
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_user_may_view_the_rakshak_settings_form()
    {
        $this->get(route('rakshak.settings.edit'))
            ->assertStatus(200)
            ->assertSee('Enable Two Factor Authentication')
            ->assertSee('Two Factor Channel')
            ->assertSee('Two Factor Control');
    }

    /** @test */
    public function authorized_user_may_update_rakshak_settings()
    {
        $settings = RakshakSetting::first();
        $this->assertFalse($settings->enable_2fa);
        $settings->enable_2fa = true;

        $this->putJson(route('rakshak.settings.update'), $settings->toArray())
            ->assertStatus(200);

        $this->assertTrue(RakshakSetting::first()->enable_2fa);
    }

    /** @test */
    public function when_rakshak_settings_are_updated_cache_is_also_updated()
    {
        Rakshak::loadCache();
        $settings = RakshakSetting::first();
        $this->assertEquals(0, Cache::get('rakshak.enable_2fa'));
        $this->assertEquals('email', $settings->channel_2fa);
        $this->assertEquals('admin', $settings->control_level_2fa);
        $settings->enable_2fa = 1;
        $settings->channel_2fa = 'sms';
        $settings->control_level_2fa = 'user';

        $this->putJson(route('rakshak.settings.update'), $settings->toArray());

        $this->assertEquals(1, Cache::get('rakshak.enable_2fa'));
        $this->assertEquals('sms', $settings->channel_2fa);
        $this->assertEquals('user', $settings->control_level_2fa);
    }
}
