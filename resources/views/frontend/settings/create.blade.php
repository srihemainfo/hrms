@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.setting.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.settings.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label for="no_of_periods">{{ trans('cruds.setting.fields.no_of_periods') }}</label>
                            <input class="form-control" type="text" name="no_of_periods" id="no_of_periods" value="{{ old('no_of_periods', '') }}">
                            @if($errors->has('no_of_periods'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('no_of_periods') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.setting.fields.no_of_periods_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="no_of_semester">{{ trans('cruds.setting.fields.no_of_semester') }}</label>
                            <input class="form-control" type="text" name="no_of_semester" id="no_of_semester" value="{{ old('no_of_semester', '') }}">
                            @if($errors->has('no_of_semester'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('no_of_semester') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.setting.fields.no_of_semester_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="semester_type">{{ trans('cruds.setting.fields.semester_type') }}</label>
                            <input class="form-control" type="text" name="semester_type" id="semester_type" value="{{ old('semester_type', '') }}">
                            @if($errors->has('semester_type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('semester_type') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.setting.fields.semester_type_helper') }}</span>
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