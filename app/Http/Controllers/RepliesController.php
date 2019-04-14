<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use Illuminate\Http\Request;
use App\Thread;
use App\Reply;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param $channel
     * @param \App\Thread
     * @return mixed
     */
    public function index($channel, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param integer $channelId
     * @param \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $channelId, Thread $thread, CreatePostRequest $createPostRequest)
    {
        if ($thread->locked) {
            return response('Thread is locked.', 422);
        }

        return $thread->addReply([
            'body' => $request->body,
            'user_id' => auth()->id(),
        ])->load('owner');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Reply $reply
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return \App\Reply
     */
    public function update(Request $request, Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->update(['body' => $request->body]);

        return $reply->fresh();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reply $reply
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['msg' => 'Deletado.']);
        }

        return back();
    }
}
