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
            'name' => 'administrator',
            'email' => 'administrator@teste.com',
            'password' => bcrypt('123')
        ]);

        User::create([
            'name' => 'teste',
            'email' => 'teste@teste.com',
            'password' => bcrypt('123')
        ]);

        // $thread = factory(Thread::class)->create();

        // $thread->subscribe($admin->id);

        // $thread->addReply([
        //     'body' => $faker->paragraph,
        //     'user_id' => factory(User::class)->create()->id
        // ]);

        $this->call(ChannelsTableSeeder::class);
    }
}
