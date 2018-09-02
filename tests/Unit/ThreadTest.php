<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use App\Thread;
use App\Reply;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Auth\User;

class ThreadTest extends TestCase
{

    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setUp();
        $this->thread = factory(Thread::class)->create();
    }
    
    /** @test */
    public function a_thread_has_replies()
    {
        $this->assertInstanceOf(Collection::class, $this->thread->replies);
    }

    /** @test */
    public function a_thread_has_a_creator ()
    {
        $this->assertInstanceOf(User::class, $this->thread->creator);
    }
}