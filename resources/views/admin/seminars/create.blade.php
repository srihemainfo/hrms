@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.seminar.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.seminars.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="user_name_id">{{ trans('cruds.seminar.fields.user_name') }}</label>
                <select class="form-control select2 {{ $errors->has('user_name') ? 'is-invalid' : '' }}" name="user_name_id" id="user_name_id">
                    @foreach($user_names as $id => $entry)
                        <option value="{{ $id }}" {{ old('user_name_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user_name'))
                    <span class="text-danger">{{ $errors->first('user_name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.seminar.fields.user_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="topic">{{ trans('cruds.seminar.fields.topic') }}</label>
                <input class="form-control {{ $errors->has('topic') ? 'is-invalid' : '' }}" type="text" name="topic" id="topic" value="{{ old('topic', '') }}">
                @if($errors->has('topic'))
                    <span class="text-danger">{{ $errors->first('topic') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.seminar.fields.topic_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="remark">{{ trans('cruds.seminar.fields.remark') }}</label>
                <input class="form-control {{ $errors->has('remark') ? 'is-invalid' : '' }}" type="text" name="remark" id="remark" value="{{ old('remark', '') }}">
                @if($errors->has('remark'))
                    <span class="text-danger">{{ $errors->first('remark') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.seminar.fields.remark_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="seminar_date">{{ trans('cruds.seminar.fields.seminar_date') }}</label>
                <input class="form-control date {{ $errors->has('seminar_date') ? 'is-invalid' : '' }}" type="text" name="seminar_date" id="seminar_date" value="{{ old('seminar_date') }}">
                @if($errors->has('seminar_date'))
                    <span class="text-danger">{{ $errors->first('seminar_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.seminar.fields.seminar_date_helper') }}</span>
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