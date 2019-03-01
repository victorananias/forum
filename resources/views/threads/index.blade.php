@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @forelse($threads as $thread)
                    
                    <div class="card mb-5">
                        <div class="card-header bg-dark text-light">
                            <div class="level">
                                <h4 class="flex">
                                    <a class="text-light" href="{{ $thread->path() }}">
                                        @if (auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                                            <strong>{{ $thread->title }}</strong>
                                        @else
                                            {{ $thread->title }}
                                        @endif
                                    </a>
                                </h4>
                                <a class="text-light" href="{{ $thread->path() }}">
                                    <strong class="float-right">{{ $thread->replies_count }} {{ str_plural('resposta', $thread->replies_count) }}</strong>
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="body">{{ $thread->body }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-dark">Não existem threads neste channel</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
