@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h1 class="pb-2 mt-4 mb-2 border-bottom">
                    {{ $profileUser->name }}
                    <small> cadastrado {{ $profileUser->created_at->diffForHumans() }}</small>
                </h1>
        
                @foreach($threads as $thread)
                    <div class="card mb-4">
                        <div class="card-header bg-dark text-light">
                            <div class="level">
                                <span class="flex">
                                    <a class="font-weight-bold" href="/profiles/{{  $thread->creator->name }}">{{ $thread->creator->name }}</a> publicou:
                                    <a class="font-weight-bold" href="{{ $thread->path() }}">{{ $thread->title }}</a>
                                </span>
                                <span>
                                    {{ $thread->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            {{ $thread->body }}
                        </div>
                    </div>
                @endforeach
        
                {{ $threads->links() }}
            </div>
        </div>
    </div>

@endsection