<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TrendingThreadsTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();

        \Redis::del('trending_threads');
    }
    use RefreshDatabase;

    /** @test */
    public function it_increments_a_thread_score_each_time_it_is_read()
    {
        $this->assertEmpty(\Redis::zrevrange('trending_threads', 0, -1));

        $thread = factory(Thread::class)->create();

        $this->call('GET', $thread->path());

        $this->assertcount( 1, \Redis::zrevrange('trending_threads', 0, -1));

        $trending = \Redis::zrevrange('trending_threads', 0, -1);

        $this->assertCount(1, $trending);

        $this->assertEquals($thread->title, json_decode($trending[0])->title);
    }
}
