
{{-- Editing --}}
<div class="card mb-4" v-if="editing">
    <div class="card-header ">
        <input type="text" class="form-control" v-model="form.title">
    </div>

    <div class="card-body">
        <wysiwyg v-model="form.body" :value="form.body"></wysiwyg>
    </div>

    <div class="card-footer level">
        <div>
            <button class="btn btn-sm" @click="update">
                <i class="fas fa-save"></i>
            </button>

            <button class="btn btn-sm" @click="resetForm">
                <i class="fas fa-undo"></i>
            </button>
        </div>

        <button v-if="authorize('owns', thread)" class="btn btn-sm ml-auto" @click="destroy">
            <i class="fas fa-trash-alt"></i>
        </button>
    </div>

</div>

{{-- Viewing the thread --}}
<div class="card mb-4" v-else>
    <div class="card-header">
        <div class="level">
            <img class="mr-1" width="25" src="{{ $thread->creator->avatar_path }}" onerror="this.src = '/avatar.png'">

            <span class="flex">
                <a class="font-weight-bold" href="/profiles/{{ $thread->creator->username }}">
                    {{ $thread->creator->username }}
                </a> published: <span v-text="title"></span>
            </span>

            <lock-button v-if="authorize('isAdmin')"></lock-button>

        </div>
    </div>

    <div class="card-body" v-html="body"></div>

    <div class="card-footer level" v-if="authorize('owns', thread)">
        <button class="btn btn-sm" @click="editing = true" v-if="authorize('owns', thread)">
            <i class="fas fa-pen"></i>
        </button>

        <button class="btn btn-sm ml-auto" @click="destroy">
            <i class="fas fa-trash-alt"></i>
        </button>
    </div>

</div>