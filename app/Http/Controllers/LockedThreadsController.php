<?php

namespace App\Http\Controllers;

use App\Thread;

class LockedThreadsController extends Controller
{

    /**
     *
     * Update the specified resource in storage.
     *
     * @param \App\Thread $thread
     * @return mixed
     */
    public function store(Thread $thread)
    {
        $thread->lock();
    }
}
