<?php

namespace Thinkstudeo\Rakshak\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Thinkstudeo\Rakshak\Rakshak;
use Thinkstudeo\Rakshak\Tests\TestCase;

class RakshakTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_is_instantiable()
    {
        $rakshak = new Rakshak;

        $this->assertInstanceOf('Thinkstudeo\Rakshak\Rakshak', $rakshak);
    }
}
