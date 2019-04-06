<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 06/04/19
 * Time: 08:26
 */

namespace App;

use Illuminate\Support\Facades\Redis;

class Trending
{

    /**
     * @return string
     */
    protected function cacheKey(): string
    {
        return app()->environment('testing') ? 'testing_trending_threads' : 'trending_threads';
    }

    public function get()
    {
        return array_map('json_decode', Redis::zrevrange($this->cacheKey(), 0 , 4));
    }

    public  function push($thread)
    {
        Redis::zincrby($this->cacheKey(), 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path(),
        ]));
    }

    public function reset()
    {
        Redis::del($this->cacheKey());
    }
}