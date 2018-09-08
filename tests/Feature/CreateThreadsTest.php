<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Auth\AuthenticationException;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guests_may_not_create_threads()
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);
        $this->post('/threads', []);
    }

    /** @test */
    public function guests_cannot_see_the_create_thread_page()
    {
        $this->get('/threads/create')
            ->assertRedirect('login');

    }

    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $thread = factory(Thread::class)->make();

        $this->post('/threads', $thread->toArray());

        $this->get($thread->path())
             ->assertSee($thread->title)
             ->assertSee($thread->body);
    }
}
