<reply :attributes="{{ $reply }}" inline-template v-cloak>
    <div class="card mb-4" id="reply-{{ $reply->id }}"> 
        <div class="card-header">
            <div class="level">
                <div class="flex">
                    <a href="/profiles/{{ $reply->owner->name }}">{{ $reply->owner->name }}</a> disse {{ $reply->created_at->diffForHumans() }}...
                </div>
                <div>
                    <favorite :reply="{{ $reply }}"></favorite>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>
                <button class="btn btn-sm btn-primary" @click="update()">Update</button>
                <button class="btn btn-sm btn-link" @click="editing = false">Cancel</button>
            </div>
            <div v-else v-text='body'></div>
        </div>

        @can('update', $reply)
            <div class="card-footer level">
                <button class="btn btn-secondary btn-sm mr-2" @click="editing = true">Edit</button>
                <button class="btn btn-danger btn-sm mr-2" @click="destroy()">Delete</button>
            </div>
        @endcan 
    </div>
</reply>