<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;
use App\Reply;
use App\Inspections\Spam;

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
     * @param App\Thread $thread
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
     * @param \App\Inspections\Spam $spam
     * @param integer $channelId
     * @param \App\Thread $thread
     * @return void
     */
    public function store(Request $request, $channelId, Thread $thread)
    {
        try {
            $this->validateReply();

            $reply = $thread->addReply([
                'body' => $request->body,
                'user_id' => auth()->id(),
            ]);
        } catch (\Exception $e) {
            return response('Desculpe, sua resposta não pode ser salva.', 422);
        }

        return $reply->load('owner');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reply $reply)
    {
        try {
            $this->validateReply();

            $this->authorize('update', $reply);

            $reply->update(['body' => $request->body]);
        } catch (\Exception $e) {
            return response('Desculpe, sua resposta não pode ser salva.', 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['mensagem' => 'Deletado.']);
        }

        return back();
    }

    public function validateReply()
    {
        request()->validate(['body' => 'required']);

        resolve(Spam::class)->detect(request('body'));
    }
}
