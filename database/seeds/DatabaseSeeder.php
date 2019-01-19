<?php

use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ThreadsTableSeeder::class);

        User::create([
            'name'=> 'administrator',
            'email'=> 'administrator@teste.com',
            'password'=> bcrypt('123')
        ]);

        User::create([
            'name'=> 'teste',
            'email'=> 'teste@teste.com',
            'password'=> bcrypt('123')
        ]);
    }
}
