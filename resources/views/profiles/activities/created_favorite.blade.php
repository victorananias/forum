@component('profiles.activities.activity')
    @slot('heading')
        <a class="font-weight-bold" href="/profiles/{{  $profileUser->username }}">
            {{ $profileUser->username }}
        </a>
        favoritou 
        <a class="font-weight-bold" href="{{  $activity->subject->favorited->path() }}">
            uma resposta.
        </a>
    @endslot
    @slot('body')
        {!! $activity->subject->favorited->htmlBody !!}
    @endslot
@endcomponent
