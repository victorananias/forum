@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-light">Threads</div>

                <div class="card-body">

                   @foreach($threads as $thread)

                    <article>
                        <div class="level">
                            <h4 class="flex">
                                <a class="text-dark" href="{{ $thread->path() }}">{{ $thread->title }}</a>
                            </h4>
                            <a class="text-dark" href="{{ $thread->path() }}">
                                <strong class="float-right">{{ $thread->replies_count }} {{ str_plural('resposta', $thread->replies_count) }}</strong>
                            </a>
                        </div>
                        <div class="body">{{ $thread->body }}</div>
                    </article>
                    <hr>

                   @endforeach

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
