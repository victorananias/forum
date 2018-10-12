<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Reply;

class FavoritesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_can_not_favorite_anything()
    {
        $this->withExceptionHandling()
             ->post('replies/1/favorites')
             ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $reply = factory(Reply::class)->create();

        $this->actingAs(factory(User::class)->create());

        $this->post("replies/{$reply->id}/favorites");

        $this->assertCount(1, $reply->favorites);
    }
}
