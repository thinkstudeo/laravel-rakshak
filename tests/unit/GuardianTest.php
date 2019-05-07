<?php

namespace Thinkstudeo\Guardian\Tests\Unit;

use Thinkstudeo\Guardian\Guardian;
use Thinkstudeo\Guardian\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GuardianTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_is_instantiable()
    {
        $guardian = new Guardian;

        $this->assertInstanceOf('Thinkstudeo\Guardian\Guardian', $guardian);
    }
}
