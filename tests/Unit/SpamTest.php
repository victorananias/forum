<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Inspections\Spam;

class SpamTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_validates_spam()
    {
        $spam = new Spam;

        $this->assertFalse($spam->detect('Innocent reply here'));

        $this->expectException('Exception');

        $spam->detect('Yahoo costumer support');
    }

    /** @test */
    public function it_checks_for_any_key_beight_held_down()
    {
        $spam = new Spam;

        $this->expectException('Exception');

        $spam->detect('Hello world aaaaaaaaaaaa');
    }
}
