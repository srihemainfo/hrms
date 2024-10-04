@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ 'Shift' }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.Shift.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="topic">{{ 'Name' }}</label>
                <input class="form-control" type="text" name="Name" id="topic" value="">
            </div>
            <div class="form-group">
                <label for="start-time">Start Time:</label>
                <input  class="form-control" type="time" id="start-time" name="time">

            </div>
            <div class="form-group">
                <label for="end-time">End Time:</label>
                <input  class="form-control" type="time" id="end-time" name="endTime">

            </div>

            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection
