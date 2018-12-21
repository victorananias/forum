@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-dark text-light">
                    <h5>
                        <a class="font-weight-bold" href="/profiles/{{ $thread->creator->name }}">{{ $thread->creator->name }}</a> publicou:
                        {{ $thread->title }}
                    </h5>
                </div>
                <div class="card-body">
                    {{ $thread->body }}
                </div>
            </div>

            @foreach($replies as $reply)
                @include('threads.reply')
            @endforeach

            {{ $replies->links() }}

            @if(auth()->check())
                <form action="{{ $thread->path().'/replies' }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <textarea class="form-control" name="body" rows="5" 
                            placeholder="Tem algo a dizer?"></textarea>
                    </div>
                    <button type="submit" class="btn btn-dark float-right">Comentar</button>
                </form>
            @else 
                <p class="text-center"><a href="{{ route('login') }}">Entre</a> para participar da discução.</p>
            @endif

        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    Essa thread foi publicada {{ $thread->created_at->diffForHumans() }} por 
                    <a href="/profiles/{{ $thread->creator->name }}">{{ $thread->creator->name }}</a> 
                    a atualmente possui {{ $thread->replies_count }} {{ str_plural('comentário', $thread->replies_count) }}.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
