<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Thread;
use App\Reply;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name'=> 'administrator',
            'email'=> 'administrator@teste.com',
            'password'=> bcrypt('123')
        ]);

        User::create([
            'name'=> 'teste',
            'email'=> 'teste@teste.com',
            'password'=> bcrypt('123')
        ]);

        $thread = factory(Thread::class)->create();

        $thread->subscribe($admin->id);

        
        foreach (range(1, 10) as $i) {
            $reply = factory(Reply::class)->make();

            $thread->addReply([
                'body' => $reply->body,
                'user_id' => $reply->user_id
            ]);
        }

        
        $this->call(ThreadsTableSeeder::class);
    }
}
