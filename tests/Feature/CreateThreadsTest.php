<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Channel;
use App\Reply;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_create_threads()
    {
        $this->withExceptionHandling();

        $this->get('/threads/create')
             ->assertRedirect('/login');

        $this->post('/threads', [])
             ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        $this->actingAs(factory(User::class)->create());

        $thread = factory(Thread::class)->make();

        $response = $this->post('/threads', $thread->toArray());

        $this->get($response->headers->get('location'))
             ->assertSee($thread->title)
             ->assertSee($thread->body);
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
             ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
             ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
        factory(Channel::class, 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function a_thread_can_be_deleted()
    {
        $this->actingAs(factory(User::class)->create());

        $thread = factory(Thread::class)->create();
        $reply = factory(Reply::class)->create(['thread_id' => $thread->id]);
        
        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    /** @test */
    public function guests_cannot_delete_threads()
    {
        $this->withExceptionHandling();

        $thread = factory(Thread::class)->create();

        $this->delete($thread->path())->assertRedirect('/login');
    }

    /** @test2 */
    public function threads_may_only_be_deleted_by_those_who_have_permission()
    {
    }

    
    private function publishThread($overrides = [])
    {
        $this->withExceptionHandling();

        $thread = factory(Thread::class)->make($overrides);

        $this->actingAs(factory(User::class)->create());

        return $this->post('/threads', $thread->toArray());
    }
}
