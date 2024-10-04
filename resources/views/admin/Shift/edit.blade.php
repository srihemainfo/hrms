@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.seminar.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.Shift.update", [$request->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="topic">{{ 'Name' }}</label>
                <input class="form-control" type="text" name="Name" id="topic" value="{{ $request->Name }}">
            </div>
            <div class="form-group">
                <label for="start-time">Start Time:</label>
                <input  class="form-control" type="time" id="start-time" name="time" value="{{ $request->time }}">

            </div>
            <div class="form-group">
                <label for="end-time">End Time:</label>
                <input  class="form-control" type="time" id="end-time" name="endTime" value="{{ $request->endTime }}">

            </div>

            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
            </div>
        </form>
</div>




@endsection
