<?php

namespace App\Http\Controllers;

use App\Trending;
use Illuminate\Http\Request;
use App\Thread;
use App\Channel;
use App\Filters\ThreadFilter;
use App\Rules\SpamFree;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Channel $channel
     * @param ThreadFilter $filters
     * @param Trending $trending
     * @return mixed
     */
    public function index(Channel $channel, ThreadFilter $filters, Trending $trending)
    {
        $threads = $this->getThreads($channel, $filters);

        if (request()->wantsJson()) {
            return $threads;
        }

        return view('threads.index', [
            'threads' => $threads,
            'trending' => $trending->get()
        ]);
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
        $response = (new \GuzzleHttp\Client())->request(
            'POST',
            'https://google.com/recaptcha/api/siteverify',
            [
                'form_params' => [
                    'secret' => config('services.recaptcha.server_secret'),
                    'response' => $request->input('g-recaptcha-response'),
                    'remoteip' => $_SERVER['REMOTE_ADDR']
                ]
            ]
        );

        $r = json_decode($response->getBody()->getContents());

        if (! $r->success) {
            throw new \Exception('Recaptcha failed');
        }

        $request->validate([
            'title' => ['required', new SpamFree],
            'body' => ['required', new SpamFree],
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
     * @param \App\Trending $trending
     * @return void
     */
    public function show($channelId, Thread $thread, Trending $trending)
    {
        if ($user = auth()->user()) {
            $user->read($thread);
        }

        $trending->push($thread);

        $thread->increment('visits');

        return view('threads.show', [
            'thread' => $thread,
            'replies' => $thread->replies()->paginate(5)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $channelId
     * @param Thread $thread
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($channelId, Thread $thread)
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

        return $threads->paginate(20);
    }
}
