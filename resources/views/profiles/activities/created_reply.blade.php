@component('profiles.activities.activity')
    @slot('heading')
        <a class="font-weight-bold" href="/profiles/{{  $profileUser->name }}">
            {{ $profileUser->name }}
        </a>
        respond to
        <a class="font-weight-bold" href="{{  $activity->subject->thread->path() }}">
            "{{ $activity->subject->thread->title }}"
        </a>
    @endslot
    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent
