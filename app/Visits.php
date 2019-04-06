<?php

namespace App;


class Visits
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function record()
    {
        \Redis::incr($this->cacheKey());
    }

    public function count()
    {
        return \Redis::get($this->cacheKey()) ?? 0;
    }

    public function reset()
    {
        \Redis::del($this->cacheKey());
    }

    /**
     * @return string
     */
    protected function cacheKey(): string
    {
        return "threads.{$this->model->id}.visits";
    }
}