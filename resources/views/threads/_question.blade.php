
{{-- Editing --}}
<div class="card mb-4" v-if="editing" v-cloak>
    <div class="card-header ">
        <input type="text" class="form-control" v-model="form.title">
    </div>

    <div class="card-body">
        <textarea class="form-control" rows="10" v-model="form.body"></textarea>
    </div>

    <div class="card-footer level">
        <div>
            <button class="btn" @click="update">
                <i class="fas fa-save"></i>
            </button>

            <button class="btn" @click="resetForm">
                <i class="fas fa-undo"></i>
            </button>
        </div>

        <button v-if="authorize('owns', thread)" type="submit" class="btn ml-auto" @click="destroy">
            <i class="fas fa-trash-alt"></i>
        </button>
    </div>

</div>

{{-- Viewing the thread --}}
<div class="card mb-4" v-else>
    <div class="card-header ">
        <div class="level">
            <img class="mr-1" width="25" src="{{ $thread->creator->avatar_path }}" alt="{{ $thread->creator->name }}">

            <span class="flex">
                <a class="font-weight-bold" href="/profiles/{{ $thread->creator->username }}">
                    {{ $thread->creator->username }}
                </a> published: <span v-text="title"></span>
            </span>

            <lock-button></lock-button>

        </div>
    </div>

    <div class="card-body" v-text="body"></div>

    <div class="card-footer level">
        <button class="btn" @click="editing = true">
            <i class="fas fa-pen"></i>
        </button>

        <button v-if="authorize('owns', thread)" type="submit" class="btn ml-auto" @click="destroy">
            <i class="fas fa-trash-alt"></i>
        </button>
    </div>

</div>