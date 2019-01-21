@component('profiles.activities.activity')
    @slot('heading')
        <a class="font-weight-bold" href="/profiles/{{  $profileUser->name }}">
            {{ $profileUser->name }}
        </a>
        favoritou 
        <a class="font-weight-bold" href="{{  $activity->subject->favorited->path() }}">
            uma resposta.
        </a>
    @endslot
    @slot('body')
        {{ $activity->subject->favorited->body }}
    @endslot
@endcomponent