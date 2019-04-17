@extends('layouts.app')

@section('content')
    <search-view inline-template class="container-fluid" current-refinement="{{ request('q') }}" v-cloak>

            <ais-instant-search
                class="row"
                :search-client="searchClient"
                index-name="threads_index"
            >

                <div class="col-md-8">

                    <ais-configure query="{{ request('q')}}"/>

                    <ais-hits>
                        <div slot-scope="{ items }">
                            <div v-for="item in items" :key="item.id" class="card mb-3">
                                <div class="card-header flex">
                                    <strong>
                                        <a class="" :href="item.path">
                                            <ais-highlight attribute="title" :hit="item"/>
                                        </a>
                                    </strong>

                                    <span>posted by
                                        <a :href="'/profiles/'+item.creator.username" v-text="item.creator.name"></a>
                                    </span>
                                </div>
                                <div class="card-body" v-html="item.body">
                                    {{-- <ais-highlight attribute="body" :hit="item" /> --}}
                                </div>
                            </div>
                        </div>
                    </ais-hits>

                </div>

                <div class="col-md-4">
                    @if (count($trending))

                        <div class="card mb-2">
                            <div class="card-header">Search</div>
                            <div class="card-body">
                                <div class="form-group">
                                    <ais-search-box
                                            placeholder="Find a thread..."
                                            autofocus>
                                        <div slot-scope="{ currentRefinement, isSearchStalled, refine }">
                                            <input class="form-control"
                                                    type="search"
                                                    v-model="currentRefinement"
                                                    @input="refine($event.currentTarget.value)">
                                            <span :hidden="!isSearchStalled">Loading...</span>
                                        </div>
                                    </ais-search-box>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-2">
                            <div class="card-header">
                                Filter by Channel
                            </div>
                            <div class="card-body">
                                <ais-refinement-list attribute="channel.name"  operator="or">
                                    <div slot-scope="{ items, refine }">
                                        <div @click="refine(item.value); createURL(item.value)"
                                             v-for="item in items" :key="item.value" class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input"
                                                   :checked="item.isRefined">
                                            <label class="custom-control-label"
                                                   v-text="item.value"></label>
                                        </div>
                                    </div>
                                </ais-refinement-list>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">Trending Threads</div>
                            <div class="card-body">
                                <ul class="list-group">
                                    @foreach($trending as $thread)
                                        <li class="list-group-item">
                                            <a href="{{ url($thread->path) }}">{{ $thread->title }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                </div>

            </ais-instant-search>

    </search-view>
@endsection
