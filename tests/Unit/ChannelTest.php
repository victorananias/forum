<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;
use App\Channel;

class ChannelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_channel_consists_of_threads()
    {
        $channel = factory(Channel::class)->create();
        $thread = factory(Thread::class)->create([
            'channel_id' => $channel->id
        ]);

        $this->assertTrue($channel->threads->contains($thread));
    }
}
