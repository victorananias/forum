<?php

namespace Tests\Feature;

use App\Thread;
use http\Client\Curl\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BestReplyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_thread_creator_may_mark_any_reply_as_the_best_reply()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $thread = factory(Thread::class)->create(['user_id' => $user->id]);

    }
}
