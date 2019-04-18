@forelse($threads as $thread)

    <div class="card mb-5">
        <div class="card-header ">
            <div class="level">
                <div class="flex">
                    <a class="" href="{{ $thread->path() }}">
                        @if (auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                            <strong>{{ $thread->title }}</strong>
                        @else
                            {{ $thread->title }}
                        @endif
                    </a>

                    <span>posted by <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->username }}</a></span>

                </div>

                <a class="" href="{{ $thread->path() }}">
                    {{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="body">{!! $thread->body !!}</div>
        </div>

        <div class="card-footer">
            {{ $thread->visits }} {{ str_plural('visit', $thread->visits) }}
        </div>
    </div>
@empty
    <p>There are no threads here</p>
@endforelse
