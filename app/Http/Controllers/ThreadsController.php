<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;
use App\Channel;
use App\Filters\ThreadFilter;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilter $filters)
    {
        $threads = $this->getThreads($channel, $filters);

        return view('threads.index', compact('threads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
            'channel_id' => 'required|exists:channels,id'
        ]);

        $thread = Thread::create([
            'title' => $request->title,
            'body' => $request->body,
            'channel_id' => $request->channel_id,
            'user_id' => auth()->id()
        ]);

        return redirect($thread->path())->with([
            'aviso' => 'Sua thread foi criada.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param $channelId
     * @param \App\Thread $thread
     * @return void
     */
    public function show($channelId, Thread $thread)
    {
        return view('threads.show', [
            'thread' => $thread,
            'replies' => $thread->replies()->paginate(5)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->delete();
        
        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect('/threads');
    }

    /**
     * Fetch all relevant threads
     *
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @return mixed
     */
    protected function getThreads($channel, $filters)
    {
        $threads = Thread::filter($filters)->latest();

        if ($channel->exists) {
            $threads = $threads->where('channel_id', $channel->id);
        }

        return $threads->get();
    }
}
