@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Criar Nova Thread</div>
                    <div class="card-body">
                        <form method="POST" action="/threads">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="channel_id">Canal:</label>
                                <select name="channel_id" id="channel_id" class="form-control" required>
                                    <option value="">Escolher...</option>
                                    @foreach ($channels as $channel)
                                        <option value="{{ $channel->id }}" {{ old('channel_id') == $channel->id ? 'selected' : '' }}>
                                            {{ $channel->slug }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="title">Título:</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="body">Conteúdo:</label>
                                <textarea class="form-control" id="body" name="body" rows="8" required>{{ old('body') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-dark float-right">Publicar</button>
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
