<?php

use Illuminate\Database\Seeder;
use App\Activity;

class ThreadsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $threads = factory(\App\Thread::class, 50)->create();

        $threads->each(function ($thread) {
            $thread->activities()->create([
                'type' => 'created_thread',
                'user_id' => $thread->user_id
            ]);

            factory(\App\Reply::class, 10)->create([
                'thread_id' => $thread->id
            ]);
        });
    }
}
