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
        $channels = ["Algolia", "Bootstrap", "JavaScript", "Laravel", "PHP", "Pusher", "Redis", "Testing", "Vue"];

        foreach ($channels as $channel) {
            Channel::create([
                'name' => $channel,
                'slug' => str_slug($channel)
            ]);
        }
    }
}
