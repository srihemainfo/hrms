@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.guestLecture.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.guest-lectures.update", [$guestLecture->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="user_name_id">{{ trans('cruds.guestLecture.fields.user_name') }}</label>
                <select class="form-control select2 {{ $errors->has('user_name') ? 'is-invalid' : '' }}" name="user_name_id" id="user_name_id">
                    @foreach($user_names as $id => $entry)
                        <option value="{{ $id }}" {{ (old('user_name_id') ? old('user_name_id') : $guestLecture->user_name->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user_name'))
                    <span class="text-danger">{{ $errors->first('user_name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.guestLecture.fields.user_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="topic">{{ trans('cruds.guestLecture.fields.topic') }}</label>
                <input class="form-control {{ $errors->has('topic') ? 'is-invalid' : '' }}" type="text" name="topic" id="topic" value="{{ old('topic', $guestLecture->topic) }}">
                @if($errors->has('topic'))
                    <span class="text-danger">{{ $errors->first('topic') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.guestLecture.fields.topic_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="remarks">{{ trans('cruds.guestLecture.fields.remarks') }}</label>
                <input class="form-control {{ $errors->has('remarks') ? 'is-invalid' : '' }}" type="text" name="remarks" id="remarks" value="{{ old('remarks', $guestLecture->remarks) }}">
                @if($errors->has('remarks'))
                    <span class="text-danger">{{ $errors->first('remarks') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.guestLecture.fields.remarks_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="location">{{ trans('cruds.guestLecture.fields.location') }}</label>
                <input class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}" type="text" name="location" id="location" value="{{ old('location', $guestLecture->location) }}">
                @if($errors->has('location'))
                    <span class="text-danger">{{ $errors->first('location') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.guestLecture.fields.location_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="from_date_and_time">{{ trans('cruds.guestLecture.fields.from_date_and_time') }}</label>
                <input class="form-control datetime {{ $errors->has('from_date_and_time') ? 'is-invalid' : '' }}" type="text" name="from_date_and_time" id="from_date_and_time" value="{{ old('from_date_and_time', $guestLecture->from_date_and_time) }}">
                @if($errors->has('from_date_and_time'))
                    <span class="text-danger">{{ $errors->first('from_date_and_time') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.guestLecture.fields.from_date_and_time_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="to_date_and_time">{{ trans('cruds.guestLecture.fields.to_date_and_time') }}</label>
                <input class="form-control datetime {{ $errors->has('to_date_and_time') ? 'is-invalid' : '' }}" type="text" name="to_date_and_time" id="to_date_and_time" value="{{ old('to_date_and_time', $guestLecture->to_date_and_time) }}">
                @if($errors->has('to_date_and_time'))
                    <span class="text-danger">{{ $errors->first('to_date_and_time') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.guestLecture.fields.to_date_and_time_helper') }}</span>
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