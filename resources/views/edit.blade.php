@php ($nav = 'edit')
@extends('layout.app')

@section('content')
    <div class="container">
        @include('layout.errors')
        <form method="POST">
            @csrf

            <div class="form-group">
                <label for="titleInput">{{ __('app.title') }}</label>
                <input name="title" type="text" class="form-control" id="titleInput" required maxlength="150" value="{{ old('title', $project->title ?? '') }}">
            </div>
            <div class="form-group">
                <label for="descriptionInput">{{ __('app.description') }}</label>
                <textarea name="description" type="text" class="form-control" required maxlength="16777215" id="descriptionInput">{{ old('description', $project->description ?? '') }}</textarea>
            </div>
            <div class="form-group">
                <label for="statusSelect">{{ __('app.status') }}</label>
                <select class="form-control" id="statusSelect" name="status">
                    @if(isset($project))
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}" @if($status_id == $status->id) selected @endif>{{ $status->name }}</option>
                        @endforeach
                    @else
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="form-group">
                <label for="ownerNameInput">{{ __('app.owner_name') }}</label>
                <input name="owner_name" type="text" class="form-control" id="ownerName" maxlength="150" required value="{{ old("ownerName", $owner->name ?? '') }}">
            </div>
            <div class="form-group">
                <label for="ownerEmailInput">{{ __('app.owner_email') }}</label>
                <input name="owner_email" type="email" class="form-control" id="ownerEmailInput" required maxlength="150" value="{{ old("ownerEmail", $owner->email ?? '') }}">
            </div>
            <button type="submit" class="btn btn-primary">Mentés</button>
        </form>
    </div>
@endsection
