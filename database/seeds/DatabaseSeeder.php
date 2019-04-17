<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Thread;

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
            'name' => 'Administrator',
            'username' => 'administrator',
            'email' => 'administrator@teste.com',
            'password' => bcrypt('123'),
            'is_admin' => true,
            'email_verified_at' => \Carbon\Carbon::now()
        ]);

        User::create([
            'name' => 'teste',
            'username' => 'teste',
            'email' => 'teste@teste.com',
            'password' => bcrypt('123')
        ]);

        $thread = factory(Thread::class)->create();

        $thread->subscribe($admin->id);

//         $thread->addReply([
//             'body' => $faker->paragraph,
//             'user_id' => factory(User::class)->create()->id
//         ]);

        $this->call(ChannelsTableSeeder::class);
        $this->call(ThreadsTableSeeder::class);
    }
}
