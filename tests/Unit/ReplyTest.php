<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Reply;
use Illuminate\Foundation\Auth\User;
use Carbon\Carbon;

class ReplyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_owner()
    {
        $reply = factory(Reply::class)->create();
        $this->assertInstanceOf(User::class, $reply->owner);
    }

    /** @test */
    public function it_knows_if_it_was_just_published()
    {
        $reply = factory(Reply::class)->create();
        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();
        $this->assertFalse($reply->wasJustPublished());
    }

    /** @test */
    public function it_can_detect_all_mentioned_users_in_the_body()
    {
        $reply = factory(Reply::class)->create([
            'body' => '@Maria wants to talk to @Joao'
        ]);

        $this->assertEquals(['Maria', 'Joao'], $reply->mentionedUsers());
    }

    /** @test */
    public function it_wraps_mentioned_usernames_in_the_body_within_anchor_tags()
    {

        $reply = factory(Reply::class)->create([
            'body' => 'Hello @Cool-Maria_.'
        ]);

        $this->assertEquals(
            'Hello <a href="/profiles/Cool-Maria_">@Cool-Maria_</a>.',
            $reply->body
        );
    }
}
