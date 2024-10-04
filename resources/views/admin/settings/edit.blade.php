@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.setting.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.settings.update", [$setting->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="no_of_periods">{{ trans('cruds.setting.fields.no_of_periods') }}</label>
                <input class="form-control {{ $errors->has('no_of_periods') ? 'is-invalid' : '' }}" type="text" name="no_of_periods" id="no_of_periods" value="{{ old('no_of_periods', $setting->no_of_periods) }}">
                @if($errors->has('no_of_periods'))
                    <span class="text-danger">{{ $errors->first('no_of_periods') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.setting.fields.no_of_periods_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="no_of_semester">{{ trans('cruds.setting.fields.no_of_semester') }}</label>
                <input class="form-control {{ $errors->has('no_of_semester') ? 'is-invalid' : '' }}" type="text" name="no_of_semester" id="no_of_semester" value="{{ old('no_of_semester', $setting->no_of_semester) }}">
                @if($errors->has('no_of_semester'))
                    <span class="text-danger">{{ $errors->first('no_of_semester') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.setting.fields.no_of_semester_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="semester_type">{{ trans('cruds.setting.fields.semester_type') }}</label>
                <input class="form-control {{ $errors->has('semester_type') ? 'is-invalid' : '' }}" type="text" name="semester_type" id="semester_type" value="{{ old('semester_type', $setting->semester_type) }}">
                @if($errors->has('semester_type'))
                    <span class="text-danger">{{ $errors->first('semester_type') }}</span>
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



@endsection