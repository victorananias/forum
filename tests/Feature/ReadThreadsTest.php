<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;
use App\Reply;
use App\Channel;
use App\User;

class ReadThreadsTest extends TestCase
{
    /*
    |
    | Para cada teste sera migrado (migrate)
    | e após será revertido (rollback)
    |
    */
    use RefreshDatabase;

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
    public function a_user_can_filter_threads_according_to_a_channel()
    {
        $myChannel = factory(Channel::class)->create();

        $threadInChannel = factory(Thread::class)->create([
            'channel_id' => $myChannel->id
        ]);

        $otherChannel = factory(Channel::class)->create();
        $threadNotInChannel = factory(Thread::class)->create(['channel_id' => $otherChannel->id]);

        $this->get("/threads/{$myChannel->slug}")
             ->assertSee($threadInChannel->title)
             ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_any_username()
    {
        $user = factory(User::class)->create(['name' => 'John Doe']);

        $this->actingAs($user);

        $threadByUser = factory(Thread::class)->create(['user_id' => $user->id]);
        $threadNotByUser = factory(Thread::class)->create();

        $this->get("/threads?by={$user->username}")
             ->assertSee($threadByUser->title)
             ->assertDontSee($threadNotByUser->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_popularity()
    {
        $threadWithZeroReplies = factory(Thread::class)->create();
        $threadWithTwoReplies = factory(Thread::class)->create();
        $threadWithThreeReplies = factory(Thread::class)->create();

        factory(Reply::class, 2)->create(['thread_id' => $threadWithTwoReplies->id]);
        factory(Reply::class, 3)->create(['thread_id' => $threadWithThreeReplies->id]);

        $this->get('/threads?popular=1')->assertSeeInOrder([
            $threadWithThreeReplies->title,
            $threadWithTwoReplies->title,
            $threadWithZeroReplies->title
        ]);
    }

    /** @test */
    public function a_user_can_filter_threads_by_those_that_are_unanswered()
    {
        $thread = factory(Thread::class)->create();
        
        factory(Reply::class)->create(['thread_id' => $thread->id]);

        $response = $this->getJson('threads?unanswered=1')->json();

        $this->assertCount(1, $response['data']);
    }

    /** @test */
    public function a_user_can_request_all_replies_for_a_given_thread()
    {
        $reply = factory(Reply::class)->create(['thread_id' => $this->thread->id], 2);

        $response = $this->getJson($this->thread->path().'/replies')->json();

        $this->assertCount(1, $response['data']);
    }
}
