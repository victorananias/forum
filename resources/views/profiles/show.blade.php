@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h1 class="pb-2 mt-4 mb-2 border-bottom">
                    {{ $profileUser->username }}
                    <small> joined {{ $profileUser->created_at->diffForHumans() }}</small>
                </h1>

                @can('update', $profileUser)
                    <form method="POST" action="{{ route('avatar', $profileUser) }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input name="avatar" type="file">
                        <button class="btn btn-primary" type="submit">submit</button>
                    </form>
                @endcan

                <img src="{{ $profileUser->avatar() }}">

                @forelse($activities as $date => $activity)
                    <h5 class="pb-2 mt-4 mb-2 border-bottom">
                        {{ $date }}
                    </h5>

                    @foreach($activity as $record)

                        @if (view()->exists("profiles.activities.{$record->type}"))
                            @include("profiles.activities.{$record->type}", ['activity' => $record])
                        @endif
                        
                    @endforeach
                    
                @empty
                    <p>This user has no activities.</p>
                @endforelse
                
            </div>
        </div>
    </div>

@endsection
