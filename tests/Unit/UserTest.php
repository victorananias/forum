<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Reply;
use App\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_fetch_their_most_recent_reply()
    {
        $user = factory(User::class)->create();

        $reply = factory(Reply::class)->create(['user_id' => $user->id]);

        $this->assertEquals($reply->id, $user->lastReply->id);
    }

    /** @test */
    public function a_user_may_have_a_valid_username()
    {

        User::create([
            'username' => 'johndoe',
            'name' => 'John Doe',
            'email' => 'JohnDoe@teste.com',
            'password' => 'pass',
        ]);

        $this->assertDatabaseHas('users', [ 'username' => 'johndoe']);

        $this->expectException('Exception');

        User::create([
            'username' => 'john doe',
            'name' => 'John Doe',
            'email' => 'JohnDoe2@teste.com',
            'password' => 'pass',
        ]);
    }

    /** @test */
    public function a_user_can_determine_their_avatar_path()
    {
        $user = factory(User::class)->create();

        $this->assertEquals(asset('storage/avatars/default.jpg'), $user->avatar());

        $user->avatar_path = 'avatars/me.jpg';

        $this->assertEquals(asset('storage/avatars/me.jpg'), $user->avatar());

    }
}
