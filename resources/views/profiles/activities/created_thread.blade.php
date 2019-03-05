@component('profiles.activities.activity')
    @slot('heading')
        <a class="font-weight-bold" href="/profiles/{{  $profileUser->name }}">
            {{ $profileUser->name }}
        </a> published:
        <a class="font-weight-bold" href="{{ $activity->subject->path() }}">
            {{ $activity->subject->title }}
        </a>
    @endslot
    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent
