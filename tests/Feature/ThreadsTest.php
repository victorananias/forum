<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadsTest extends TestCase
{
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
        $thread = factory(App\Thread::class)->create();

        $response = $this->get('/threads');

        $response->assertSee($thread->title);
    }
}
