<?php

namespace App\Listeners;

use App\Notifications\YouWereMentioned;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyMentionedUsers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $names = $event->reply->mentionedUsers();

        collect($event->reply->mentionedUsers())
            ->map(function($name) {
                return User::where('name', $name)->first();
            })
            ->filter()
            ->each(function($user) use($event) {
                $user->notify(new YouWereMentioned($event->reply));
            });

    }
}
