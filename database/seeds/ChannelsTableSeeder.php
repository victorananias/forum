<?php

use Illuminate\Database\Seeder;
use App\Channel;

class ChannelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Channel::create([
            'name' => 'PHP',
            'slug' => 'php'
        ]);

        Channel::create([
            'name' => 'JavaScript',
            'slug' => 'javascript'
        ]);

        Channel::create([
            'name' => 'React',
            'slug' => 'react'
        ]);

        Channel::create([
            'name' => 'Vue',
            'slug' => 'vue'
        ]);

        Channel::create([
            'name' => 'Test',
            'slug' => 'test'
        ]);
    }
}
