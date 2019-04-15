@extends('layouts.app')

@section('head')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header ">Publish new Thread</div>
                    <div class="card-body">
                        <form method="POST" action="/threads" id="new-thread-form">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="channel_id">Channel:</label>
                                <select name="channel_id" id="channel_id" class="form-control" required>
                                    <option value="">Choose...</option>
                                    @foreach ($channels as $channel)
                                        <option value="{{ $channel->id }}" {{ old('channel_id') == $channel->id ? 'selected' : '' }}>
                                            {{ $channel->slug }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="title">Title:</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="body">Content:</label>
                                <wysiwyg name="body"></wysiwyg>
                            </div>

                            <div class="form-group">
                                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.client_secret') }}"></div>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>

                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger mt-3">
                                {{ $error }}
                            </div>
                        @endforeach
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
