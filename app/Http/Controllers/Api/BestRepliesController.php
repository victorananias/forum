<?php

namespace App\Http\Controllers\Api;

use App\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BestRepliesController extends Controller
{

    /**
     *
     * Store a newly created resource in storage.
     *
     * @param Reply $reply
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Reply $reply)
    {
        $this->authorize('update', $reply->thread);

        $reply->thread->markBestReply($reply);
    }
}
