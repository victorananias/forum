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
        $this->call(ChannelsTableSeeder::class);
        $this->call(ThreadsTableSeeder::class);
    }
}
