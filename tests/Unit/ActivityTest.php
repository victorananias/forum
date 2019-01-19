<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Util\Type;
use App\Thread;
use App\User;
use App\Activity;
use App\Reply;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_records_activity_when_a_thread_is_created()
    {
        // when a user creates a thread
        $this->actingAs(factory(User::class)->create());
        $thread = factory(Thread::class)->create();
        
        // an activity must be created
        $this->assertDatabaseHas('activities', [
            'type' => 'created_thread',
            'user_id' => auth()->id(),
            'subject_id' => $thread->id,
            'subject_type' => Thread::class
        ]);

        $activity = Activity::first();

        $this->assertEquals($activity->subject->id, $thread->id);
    }
    
    /** @test */
    public function it_records_activity_when_a_reply_is_created()
    {
        // when a user creates a thread
        $this->actingAs(factory(User::class)->create());
        $reply = factory(Reply::class)->create();

        $this->assertEquals(2, Activity::count());
    }
}
