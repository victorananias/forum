<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Thread;
use App\Reply;

class ParticipteInThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unauthenticated_users_may_not_add_replies()
    {
        $this->withExceptionHandling();

        $this->post('/threads/some-channel/1/replies', [])
             ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        $user = factory(User::class)->create();

        $this->be($user);

        $thread = factory(Thread::class)->create();

        $reply = factory(Reply::class)->make(['thread_id' => $thread->id]);

        $this->post("{$thread->path()}/replies", $reply->toArray());

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);

        $this->assertEquals(1, $thread->fresh()->replies_count);
    }

    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->withExceptionHandling()
             ->actingAs(factory(User::class)->create());

        $thread = factory(Thread::class)->create();

        $reply = factory(Reply::class)->make([
            'body' => null
        ]);

        $this->post("{$thread->path()}/replies", $reply->toArray())
             ->assertStatus(422);
    }

    /** @test */
    public function unauthorized_users_cannot_delete_replies()
    {
        $this->withExceptionHandling();

        $reply = factory(Reply::class)->create();

        $this->delete("/replies/{$reply->id}")->assertRedirect('login');

        $this->actingAs(factory(User::class)->create());

        $this->delete("/replies/{$reply->id}")->assertStatus(403);

        $this->assertDatabaseHas('replies', ['id' => $reply->id]);
    }

    /** @test */
    public function authorized_users_can_delete_replies()
    {
        $this->withExceptionHandling();

        $user = factory(User::class)->create();

        $this->actingAs($user);

        $reply = factory(Reply::class)->create(['user_id' => $user->id]);

        $this->delete("/replies/{$reply->id}");

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }

    /** @test */
    public function unauthorized_users_cannot_update_replies()
    {
        $this->withExceptionHandling();

        $reply = factory(Reply::class)->create();

        $this->patch("/replies/{$reply->id}", [
            'body' => 'updated body'
        ])->assertRedirect('login');

        $user = factory(User::class)->create();
        $this->actingAs($user);

        $this->patch("/replies/{$reply->id}")->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_update_replies()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $reply = factory(Reply::class)->create(['user_id' => $user->id]);

        $updatedReply = 'that\'s updated body';

        $this->patch("/replies/{$reply->id}", ['body' => $updatedReply]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updatedReply]);
    }

    /** @test */
    public function replies_that_contain_spam_may_not_be_created()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $thread = factory(Thread::class)->create();

        $reply = factory(Reply::class)->make([
            'body' => 'Yahoo Costumer Support'
        ]);

        $this->post("{$thread->path()}/replies", $reply->toArray())
            ->assertStatus(422);
    }

    /** @test */
    public function users_my_only_reply_a_maximum_of_once_per_minute()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $thread = factory(Thread::class)->create();

        $this->post("{$thread->path()}/replies", ['body' => 'Simple reply'])
            ->assertStatus(201);

        $this->post("{$thread->path()}/replies", ['body' => 'Simple reply'])
                ->assertStatus(429);
    }
}
