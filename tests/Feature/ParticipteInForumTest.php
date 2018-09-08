<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\User;
use App\Thread;
use App\Reply;
use Illuminate\Auth\AuthenticationException;

class ParticipteInForumTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function unauthenticated_users_may_not_add_replies()
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);
        $this->post('/threads/1/replies', []);        
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        $user = factory(User::class)->create();
        
        $this->be($user);

        $thread = factory(Thread::class)->create();

        $reply = factory(Reply::class)->make(['thread_id' => $thread->id]);
        $this->post($thread->path() . '/replies', $reply->toArray());

        $this->get($thread->path())
             ->assertSee($reply->body);
    }
}
