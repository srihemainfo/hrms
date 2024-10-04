@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} Event
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.events.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="event">Event</label>
                <input class="form-control {{ $errors->has('event') ? 'is-invalid' : '' }}" type="text" name="event" id="event" value="{{ old('event', '') }}">
                @if($errors->has('event'))
                    <span class="text-danger">{{ $errors->first('event') }}</span>
                @endif
                <span class="help-block"> </span>
            </div>
            <div class="form-group">
                <button class="btn btn-outline-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection
