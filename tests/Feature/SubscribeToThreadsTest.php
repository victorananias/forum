<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;
use App\User;

class SubscribeToThreadsTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_user_can_subscribe_to_threads()
    {
        $thread = factory(Thread::class)->create();

        $user = factory(User::class)->create();

        $this->actingAs($user);
           
        $this->post("{$thread->path()}/subscriptions");

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some reply here'
        ]);

        $this->assertCount(1, auth()->user()->notifications);
    }
    
    /** @test */
    public function a_user_can_unsubscribe_to_threads()
    {
        $thread = factory(Thread::class)->create();

        $user = factory(User::class)->create();

        $this->actingAs($user);

        $thread->subscribe();

        $this->assertCount(1, $thread->fresh()->subscriptions);
           
        $this->delete("{$thread->path()}/subscriptions");

        $this->assertCount(0, $thread->fresh()->subscriptions);
    }
}
