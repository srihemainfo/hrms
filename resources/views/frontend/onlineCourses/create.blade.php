@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.onlineCourse.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.online-courses.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label for="user_name_id">{{ trans('cruds.onlineCourse.fields.user_name') }}</label>
                            <select class="form-control select2" name="user_name_id" id="user_name_id">
                                @foreach($user_names as $id => $entry)
                                    <option value="{{ $id }}" {{ old('user_name_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('user_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.onlineCourse.fields.user_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="course_name">{{ trans('cruds.onlineCourse.fields.course_name') }}</label>
                            <input class="form-control" type="text" name="course_name" id="course_name" value="{{ old('course_name', '') }}">
                            @if($errors->has('course_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('course_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.onlineCourse.fields.course_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="remark">{{ trans('cruds.onlineCourse.fields.remark') }}</label>
                            <input class="form-control" type="text" name="remark" id="remark" value="{{ old('remark', '') }}">
                            @if($errors->has('remark'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('remark') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.onlineCourse.fields.remark_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="from_date">{{ trans('cruds.onlineCourse.fields.from_date') }}</label>
                            <input class="form-control date" type="text" name="from_date" id="from_date" value="{{ old('from_date') }}">
                            @if($errors->has('from_date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('from_date') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.onlineCourse.fields.from_date_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="to_date">{{ trans('cruds.onlineCourse.fields.to_date') }}</label>
                            <input class="form-control date" type="text" name="to_date" id="to_date" value="{{ old('to_date') }}">
                            @if($errors->has('to_date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('to_date') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection