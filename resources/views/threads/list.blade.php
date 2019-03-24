@forelse($threads as $thread)

    <div class="card mb-5">
        <div class="card-header bg-dark text-light">
            <div class="level">
                <div class="flex">
                    <h5>
                        <a class="text-light" href="{{ $thread->path() }}">
                            @if (auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                                <strong>{{ $thread->title }}</strong>
                            @else
                                {{ $thread->title }}
                            @endif
                        </a>
                    </h5>

                    <h6>posted by <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->username }}</a></h6>

                </div>

                <a class="text-light" href="{{ $thread->path() }}">
                    {{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="body">{{ $thread->body }}</div>
        </div>
    </div>
@empty
    <p class="text-dark">There is no threads on this here</p>
@endforelse
