<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateThreadsTest extends TestCase
{

    public function guests_may_not_create_threads()
    {

    }

    public function an_authenticated_user_can_create_new_forum_threads()
    {
        // Give we have a signed user
        // When we hit the endpoint to create a new thread
        // Then, when we visit the thread page;
        // We should see the new thread
    }
}
