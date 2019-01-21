<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param $channelId
     * @param Thread $thread
     * @return void
     */
    public function store(Request $request, $channelId, Thread $thread)
    {
        $request->validate([
            'body' => 'required'
        ]);

        $thread->addReply([
            'body' => $request->body,
            'user_id' => auth()->id(),
        ]);

        return redirect($thread->path())->with([
            'aviso' => 'Sua resposta foi salva.'
        ]);
    }
}
