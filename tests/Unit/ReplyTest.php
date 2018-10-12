<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Reply;
use Illuminate\Foundation\Auth\User;

class ReplyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_owner()
    {
        $reply = factory(Reply::class)->create();
        $this->assertInstanceOf(User::class, $reply->owner);
    }
}
