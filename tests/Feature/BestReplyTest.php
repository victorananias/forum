<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BestReplyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_thread_creator_may_mark_any_reply_as_the_best_reply()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $thread = factory(Thread::class)->create(['user_id' => auth()->id()]);

        $replies = factory(Reply::class, 2)->create(['thread_id' => $thread->id]);

        $this->assertFalse($replies[1]->isBest);

        $this->postJson(route('best-replies.store', [$replies[1]->id]));

        $this->assertTrue($replies[1]->fresh()->isBest);
    }

    /** @test */
    public function only_the_thread_creator_may_mark_the_reply_as_best()
    {
        $this->withExceptionHandling();

        $thread = factory(Thread::class)->create();

        $replies = factory(Reply::class, 2)->create(['thread_id' => $thread->id]);

        $user = factory(User::class)->create();
        $this->actingAs($user);

        $this->postJson(route('best-replies.store', [$replies[1]->id]))
            ->assertStatus(403);
    }

    /** @test */
    public function if_the_best_reply_is_deleted_than_the_thread_is_properly_updated_to_reflected_that()
    {
        $this->actingAs(factory(User::class)->create());

        $reply = factory(Reply::class)->create(['user_id' => auth()->id()]);

        $reply->thread->markBestReply($reply);

        $this->deleteJson(route('replies.destroy', $reply));

        $this->assertNull($reply->thread->fresh()->best_reply_id);

    }
}
