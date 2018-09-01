<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Thread;
use App\Reply;

class ThreadsTest extends TestCase
{
    private $thread;

    public function setUp()
    {
        parent::setUp();
        $this->thread = factory(Thread::class)->create(); 
    }
    /*
    |
    | Para cada teste sera migrado (migrate)
    | e após será revertido (rollback)
    |
    */
    use DatabaseMigrations;

    /** @test  */
    public function a_user_can_browse_threads()
    {
        $this->get('/threads')
             ->assertSee($this->thread->title);
    }

    /** @test  */
    public function a_user_read_a_single_thread()
    {
        $this->get('/threads/'.$this->thread->id)
             ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_replies_that_are_associated_with_a_thread() {
        $reply = factory(Reply::class)->create(['thread_id' => $this->thread->id]);

        $this->get('/threads/'.$this->thread->id)
            ->assertSee($reply->body);
    }
}
