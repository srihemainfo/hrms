@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.industrialExperience.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.industrial-experiences.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label for="user_name_id">{{ trans('cruds.industrialExperience.fields.user_name') }}</label>
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
                            <span class="help-block">{{ trans('cruds.industrialExperience.fields.user_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="work_experience">{{ trans('cruds.industrialExperience.fields.work_experience') }}</label>
                            <input class="form-control" type="text" name="work_experience" id="work_experience" value="{{ old('work_experience', '') }}">
                            @if($errors->has('work_experience'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('work_experience') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.industrialExperience.fields.work_experience_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="designation">{{ trans('cruds.industrialExperience.fields.designation') }}</label>
                            <input class="form-control" type="text" name="designation" id="designation" value="{{ old('designation', '') }}">
                            @if($errors->has('designation'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('designation') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.industrialExperience.fields.designation_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="from">{{ trans('cruds.industrialExperience.fields.from') }}</label>
                            <input class="form-control date" type="text" name="from" id="from" value="{{ old('from') }}">
                            @if($errors->has('from'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('from') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.industrialExperience.fields.from_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="to">{{ trans('cruds.industrialExperience.fields.to') }}</label>
                            <input class="form-control date" type="text" name="to" id="to" value="{{ old('to') }}">
                            @if($errors->has('to'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('to') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.industrialExperience.fields.to_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="work_type">{{ trans('cruds.industrialExperience.fields.work_type') }}</label>
                            <input class="form-control" type="text" name="work_type" id="work_type" value="{{ old('work_type', '') }}">
                            @if($errors->has('work_type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('work_type') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.industrialExperience.fields.work_type_helper') }}</span>
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