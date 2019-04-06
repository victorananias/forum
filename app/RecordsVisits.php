<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 06/04/19
 * Time: 10:13
 */

namespace App;


use Illuminate\Support\Facades\Redis;

trait RecordsVisits
{

    public function recordVisit()
    {
        Redis::incr($this->visitsCacheKey());
    }

    public function visits()
    {
        return Redis::get($this->visitsCacheKey()) ?? 0;
    }

    public function resetVisits()
    {
        Redis::del($this->visitsCacheKey());
    }
}