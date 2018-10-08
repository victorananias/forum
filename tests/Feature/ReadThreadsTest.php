<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Thread;
use App\Reply;
use App\Channel;

class ReadThreadsTest extends TestCase
{
    /*
    |
    | Para cada teste sera migrado (migrate)
    | e após será revertido (rollback)
    |
    */
    use DatabaseMigrations;

    private $thread;

    public function setUp()
    {
        parent::setUp();
        $this->thread = factory(Thread::class)->create();
    }

    /** @test  */
    public function a_user_can_browse_threads()
    {
        $this->get('/threads')
             ->assertSee($this->thread->title);
    }

    /** @test  */
    public function a_user_read_a_single_thread()
    {
        $this->get($this->thread->path())
             ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        $reply = factory(Reply::class)->create(['thread_id' => $this->thread->id]);

        $this->get($this->thread->path())
            ->assertSee($reply->body);
    }

    /** @test */
    public function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = factory(Channel::class)->create();

        $threadInChannel = factory(Thread::class)->create([
            'channel_id' => $channel->id
        ]);

        $threadNotInChannel = factory(Thread::class)->create();

        $this->get("/threads/{$channel->slug}")
             ->assertSee($threadInChannel->title)
             ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_any_username()
    {
        $user = factory('App\User')->create(['name' => 'John Doe']);

        $this->actingAs($user);

        $threadByUser = factory('App\Thread')->create(['user_id' => $user->id]);
        $threadNotByUser = factory('App\Thread')->create();

        $this->get("/threads?by={$user->name}")
             ->assertSee($threadByUser->title)
             ->assertDontSee($threadNotByUser->title);
    }

    public function a_user_can_filter_threads_by_popularity()
    {
        // Given we have three threads
        // With 2 replies, 3 replies, and 0 relies respectively.
        // When I filter all threads by popularity.
        // Then they should be returned from most replies to least.
    }
}
