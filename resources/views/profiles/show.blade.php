@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h1 class="pb-2 mt-4 mb-2 border-bottom">
                    {{ $profileUser->name }}
                    <small> cadastrado {{ $profileUser->created_at->diffForHumans() }}</small>
                </h1>
        
                @foreach($activities as $date => $activity)
                    <h5 class="pb-2 mt-4 mb-2 border-bottom">
                        {{ $date }}
                    </h5>
                    @foreach($activity as $record)
                        @include("profiles.activities.{$record->type}", ['activity' => $record])
                    @endforeach
                @endforeach
                
            </div>
        </div>
    </div>

@endsection