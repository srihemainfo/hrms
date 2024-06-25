@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.seminar.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.seminars.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label for="user_name_id">{{ trans('cruds.seminar.fields.user_name') }}</label>
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
                            <span class="help-block">{{ trans('cruds.seminar.fields.user_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="topic">{{ trans('cruds.seminar.fields.topic') }}</label>
                            <input class="form-control" type="text" name="topic" id="topic" value="{{ old('topic', '') }}">
                            @if($errors->has('topic'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('topic') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.seminar.fields.topic_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="remark">{{ trans('cruds.seminar.fields.remark') }}</label>
                            <input class="form-control" type="text" name="remark" id="remark" value="{{ old('remark', '') }}">
                            @if($errors->has('remark'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('remark') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.seminar.fields.remark_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="seminar_date">{{ trans('cruds.seminar.fields.seminar_date') }}</label>
                            <input class="form-control date" type="text" name="seminar_date" id="seminar_date" value="{{ old('seminar_date') }}">
                            @if($errors->has('seminar_date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('seminar_date') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection