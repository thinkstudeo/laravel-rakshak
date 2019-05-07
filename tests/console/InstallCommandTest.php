<?php

namespace Thinkstudeo\Rakshak\Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Thinkstudeo\Rakshak\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InstallCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_adds_columns_to_the_default_users_table()
    {
        $this->artisan('guardian:install');

        $columns = Schema::getColumnListing('users');

        $this->assertContains('otp_token', $columns);
        $this->assertContains('otp_expiry', $columns);
        $this->assertContains('mobile', $columns);
        $this->assertContains('username', $columns);
    }
}
