<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentionUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {
        $joao = factory(User::class)->create(['name' => 'Joao']);
        $maria = factory(User::class)->create(['name' =>'Maria']);

        $this->actingAS($maria);

        $thread = factory(Thread::class)->create();

        $reply = factory(Reply::class)->make([
            'body' => '@Joao look at this'
        ]);

        $this->postJson($thread->path().'/replies', $reply->toArray());

        $this->assertCount(1, $joao->notifications);
    }

    /** @test */
    public function it_can_fetch_all_metioned_users_starting_with_the_given_characters()
    {
        factory(User::class)->create([
            'username' => 'john1'
        ]);

        factory(User::class)->create([
            'username' => 'john2'
        ]);

        factory(User::class)->create([
            'username' => 'jane'
        ]);

        $this->getJson('/api/users?username=john')
            ->assertJsonCount(2);
    }
}
