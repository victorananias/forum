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
    public function authenticated_users_must_first_confirm_their_email_address_before_creating_threads()
    {
        $this->publishThread()
            ->assertRedirect();
    }

    /** @test */
    public function a_user_can_create_new_forum_threads()
    {
        $this->actingAs(factory(User::class)->create());

        $thread = factory(Thread::class)->make();

        $response = $this->post('/threads', $thread->toArray());

        $this->get($response->headers->get('location'))
             ->assertSee($thread->title)
             ->assertSee($thread->body);
    }

    /** @test */
    public function a_thread_requires_a_unique_slug()
    {
        $this->actingAs(factory(User::class)->create());

        $thread = factory(Thread::class)->create([
            'title' => 'Foo Title',
            'slug' => 'foo-title'
        ]);

        $this->assertEquals($thread->fresh()->slug, 'foo-title');

        $this->post(route('threads'), $thread->toArray());
//
        $this->assertTrue(Thread::whereSlug('foo-title-2')->exists());

        $this->post(route('threads'), $thread->toArray());
//
        $this->assertTrue(Thread::whereSlug('foo-title-3')->exists());
    }

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
    public function authorized_users_can_delete_threads()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $thread = factory(Thread::class)->create(['user_id' => $user->id]);
        $reply = factory(Reply::class)->create(['thread_id' => $thread->id]);
        
        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertDatabaseMissing('activities', [
            'subject_id' => $thread->id,
            'subject_type' => Thread::class
        ]);
        $this->assertDatabaseMissing('activities', [
            'subject_id' => $reply->id,
            'subject_type' => Reply::class
        ]);
    }

    /** @test */
    public function unauthorized_users_may_not_delete_threads()
    {
        $this->withExceptionHandling();

        // Guest tenta deletar thread
        $thread = factory(Thread::class)->create();

        $this->delete($thread->path())->assertRedirect('/login');

        // Usuário não-autorizado tenta deletar thread
        $this->actingAs(factory(User::class)->create());

        $this->delete($thread->path())->assertStatus(403);
    }

    
    private function publishThread($overrides = [])
    {
        $this->withExceptionHandling();

        $thread = factory(Thread::class)->make($overrides);

        $this->actingAs(factory(User::class)->create());

        return $this->post('/threads', $thread->toArray());
    }
}
