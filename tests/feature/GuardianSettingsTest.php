<?php

namespace Thinkstudeo\Guardian\Tests\Feature;


use Thinkstudeo\Guardian\Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Thinkstudeo\Guardian\GuardianSetting;
use Illuminate\Support\Facades\Cache;
use Thinkstudeo\Guardian\Guardian;

class GuardianSettingsTest extends TestCase
{
    use RefreshDatabase;


    protected function setUp(): void
    {
        parent::setUp();

        $this->signInSuperUser();
    }

    /** @test */
    public function guests_and_unauthorized_users_may_not_view_the_guardian_settings_edit_form()
    {
        $this->signOut();
        $this->get(route('guardian.settings.edit'))
            ->assertStatus(302);

        $this->signIn();
        $this->get(route('guardian.settings.edit'))
            ->assertStatus(403);
    }

    /** @test */
    public function guests_and_unauthorized_users_may_not_update_the_guardian_settings()
    {
        $settings = GuardianSetting::first();
        $this->signOut();
        $this->put(route('guardian.settings.update'), $settings->toArray())
            ->assertStatus(302);

        $this->signIn();

        $this->put(route('guardian.settings.update'), $settings->toArray())
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_user_may_view_the_guardian_settings_form()
    {
        $this->get(route('guardian.settings.edit'))
            ->assertStatus(200)
            ->assertSee('Enable Two Factor Authentication')
            ->assertSee('Two Factor Channel')
            ->assertSee('Two Factor Control');
    }

    /** @test */
    public function authorized_user_may_update_guardian_settings()
    {
        $settings = GuardianSetting::first();
        $this->assertFalse($settings->enable_2fa);
        $settings->enable_2fa = true;

        $this->putJson(route('guardian.settings.update'), $settings->toArray())
            ->assertStatus(200);

        $this->assertTrue(GuardianSetting::first()->enable_2fa);
    }

    /** @test */
    public function when_guardian_settings_are_updated_cache_is_also_updated()
    {
        Guardian::loadCache();
        $settings = GuardianSetting::first();
        $this->assertEquals(0, Cache::get('guardian.enable_2fa'));
        $this->assertEquals('email', $settings->channel_2fa);
        $this->assertEquals('admin', $settings->control_level_2fa);
        $settings->enable_2fa = 1;
        $settings->channel_2fa = 'sms';
        $settings->control_level_2fa = 'user';

        $this->putJson(route('guardian.settings.update'), $settings->toArray());

        $this->assertEquals(1, Cache::get('guardian.enable_2fa'));
        $this->assertEquals('sms', $settings->channel_2fa);
        $this->assertEquals('user', $settings->control_level_2fa);
    }
}