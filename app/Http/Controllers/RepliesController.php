<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reply;
use App\Thread;

class RepliesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');   
    }
    
    public function store(Request $request, Thread $thread)
    {
        $thread->addReply($request->all());
        return view($thread->path());
    }
}
