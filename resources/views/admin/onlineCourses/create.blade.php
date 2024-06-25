@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.onlineCourse.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.online-courses.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="user_name_id">{{ trans('cruds.onlineCourse.fields.user_name') }}</label>
                <select class="form-control select2 {{ $errors->has('user_name') ? 'is-invalid' : '' }}" name="user_name_id" id="user_name_id">
                    @foreach($user_names as $id => $entry)
                        <option value="{{ $id }}" {{ old('user_name_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user_name'))
                    <span class="text-danger">{{ $errors->first('user_name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.onlineCourse.fields.user_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="course_name">{{ trans('cruds.onlineCourse.fields.course_name') }}</label>
                <input class="form-control {{ $errors->has('course_name') ? 'is-invalid' : '' }}" type="text" name="course_name" id="course_name" value="{{ old('course_name', '') }}">
                @if($errors->has('course_name'))
                    <span class="text-danger">{{ $errors->first('course_name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.onlineCourse.fields.course_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="remark">{{ trans('cruds.onlineCourse.fields.remark') }}</label>
                <input class="form-control {{ $errors->has('remark') ? 'is-invalid' : '' }}" type="text" name="remark" id="remark" value="{{ old('remark', '') }}">
                @if($errors->has('remark'))
                    <span class="text-danger">{{ $errors->first('remark') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.onlineCourse.fields.remark_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="from_date">{{ trans('cruds.onlineCourse.fields.from_date') }}</label>
                <input class="form-control date {{ $errors->has('from_date') ? 'is-invalid' : '' }}" type="text" name="from_date" id="from_date" value="{{ old('from_date') }}">
                @if($errors->has('from_date'))
                    <span class="text-danger">{{ $errors->first('from_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.onlineCourse.fields.from_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="to_date">{{ trans('cruds.onlineCourse.fields.to_date') }}</label>
                <input class="form-control date {{ $errors->has('to_date') ? 'is-invalid' : '' }}" type="text" name="to_date" id="to_date" value="{{ old('to_date') }}">
                @if($errors->has('to_date'))
                    <span class="text-danger">{{ $errors->first('to_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.onlineCourse.fields.to_date_helper') }}</span>
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