<div class="card mb-2"> 
    <div class="card-header">
    <div class="level">
        <div class="flex">
            <a href="/profiles/{{ $reply->owner->name }}">{{ $reply->owner->name }}</a> disse {{ $reply->created_at->diffForHumans() }}...
        </div>
        <div>
            <form method="POST" action="/replies/{{ $reply->id }}/favorites">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-default" {{ $reply->isFavorited() ? 'disabled' : '' }}>
                    {{ $reply->favorites_count }} {{ str_plural('Favorito', $reply->favorites_count) }}
                </button>
            </form>
        </div>
    </div>
    </div>
    <div class="card-body">
        {{ $reply->body }}
    </div>
</div>