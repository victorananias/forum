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

        $this->assertCount(1, $thread->subscriptions);
    }
}
