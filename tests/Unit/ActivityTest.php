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
use Illuminate\Support\Carbon;

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

    /** @test */
    public function it_fetches_a_feed_for_any_user()
    {
        $user = factory(User::class)->create();
        
        $this->actingAs($user);
        
        factory(Thread::class, 2)->create([ 'user_id' => $user->id ]);

        $user->activities()->first()->update([
            'created_at' => Carbon::now()->subWeek()
        ]);

        $feed = Activity::feed($user);

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->format('Y-m-d')
        ));
        
        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('Y-m-d')
        ));
    }
}
