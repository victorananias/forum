<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Collection;
use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Channel;
use App\User;

class ThreadTest extends TestCase
{
    use RefreshDatabase;

    protected $thread;

    public function setUp()
    {
        parent::setUp();
        $this->thread = factory(Thread::class)->create();
    }

    /** @test */
    public function a_thread_can_make_a_string_path()
    {
        $this->assertEquals(
            "/threads/{$this->thread->channel->slug}/{$this->thread->id}",
            $this->thread->path()
        );
    }

    /** @test */
    public function a_thread_has_a_creator()
    {
        $this->assertInstanceOf(User::class, $this->thread->creator);
    }

    /** @test */
    public function a_thread_has_replies()
    {
        $this->assertInstanceOf(Collection::class, $this->thread->replies);
    }

    /** @test */
    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);
        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    public function a_thread_belongs_to_a_channel()
    {
        $this->assertInstanceOf(Channel::class, $this->thread->channel);
    }

    /** @test */
    public function a_thread_can_be_subscribed_to()
    {
        $thread = factory(Thread::class)->create();

        $user = factory(User::class)->create();

        $this->actingAs($user);
        
        $thread->subscribe();

        $this->assertEquals(1, $thread->subscriptions()->where('user_id', $user->id)->count());
    }
    /** @test */
    public function a_thread_can_be_unsubscribed_from()
    {
        $thread = factory(Thread::class)->create();

        $user = factory(User::class)->create();

        $this->actingAs($user);
        
        $thread->subscribe();

        $thread->unsubscribe($user->id);

        $this->assertEquals(0, $thread->subscriptions()->where('user_id', $user->id)->count());
    }
}
