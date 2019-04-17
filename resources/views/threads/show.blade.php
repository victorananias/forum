@extends('layouts.app')

@section('content')
<thread-view inline-template :thread="{{ $thread }}">
    <div class="container">
        <div class="row">
            <div class="col-md-8">

                @include('threads._question')

                <hr>

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
<flash message="{{ session('message') }}"></flash>
@endsection
