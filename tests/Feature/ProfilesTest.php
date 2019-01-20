<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Thread;

class ProfilesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_has_a_profile()
    {
        $user = factory(User::class)->create();

        $this->get('profiles/' . $user->name)
             ->assertSee($user->name);
    }

    /** @test */
    public function profiles_display_all_threads_created_by_the_associated_user()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $thread = factory(Thread::class)->create(['user_id' => $user->id]);

        $this->get('profiles/' . $user->name)
              ->assertSee($thread->title)
              ->assertSee($thread->body);
    }
}
