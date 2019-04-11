@extends('layouts.app')

@section('content')
<thread-view inline-template :initial-replies-count="{{ $thread->replies_count }}">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header ">
                        <div class="level">
                            <img class="mr-1" width="25" src="{{ $thread->creator->avatar_path }}" alt="{{ $thread->creator->name }}">
                            <span class="flex">
                                <a class="font-weight-bold" href="/profiles/{{ $thread->creator->username }}">
                                    {{ $thread->creator->username }}
                                </a> published: {{ $thread->title }}
                            </span>
                            @can('update', $thread)
                                <form action="{{ $thread->path() }}" method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button type="submit" class="btn ">
                                        <i class="fas fa-trash-alt fa-lg"></i>
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        {{ $thread->body }}
                    </div>
                </div>

                <replies @removed="repliesCount--" @added="repliesCount++"></replies>

            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <p>
                            This thread was published {{ $thread->created_at->diffForHumans() }} by
                            <a href="/profiles/{{ $thread->creator->username }}">{{ $thread->creator->username }}</a>
                            and currently has <span v-text="repliesCount"></span> {{ str_plural('reply', $thread->replies_count) }}.
                        </p>
                        @if(auth()->check())
                        <p>
                            <subscribe-button :initial-active="{{ $thread->isSubscribedTo ? 'false' : 'true' }}"></subscribe-button>
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</thread-view>
<flash message="{{ session('aviso') }}"></flash>
@endsection
