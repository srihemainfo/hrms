@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.collegeCalender.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.college-calenders.update", [$collegeCalender->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="type">{{ trans('cruds.collegeCalender.fields.type') }}</label>
                            <input class="form-control" type="text" name="type" id="type" value="{{ old('type', $collegeCalender->type) }}">
                            @if($errors->has('type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('type') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.collegeCalender.fields.type_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="academic_year">{{ trans('cruds.collegeCalender.fields.academic_year') }}</label>
                            <input class="form-control" type="text" name="academic_year" id="academic_year" value="{{ old('academic_year', $collegeCalender->academic_year) }}">
                            @if($errors->has('academic_year'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('academic_year') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.collegeCalender.fields.academic_year_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="shift">{{ trans('cruds.collegeCalender.fields.shift') }}</label>
                            <input class="form-control" type="text" name="shift" id="shift" value="{{ old('shift', $collegeCalender->shift) }}">
                            @if($errors->has('shift'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('shift') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.collegeCalender.fields.shift_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="semester_type">{{ trans('cruds.collegeCalender.fields.semester_type') }}</label>
                            <input class="form-control" type="text" name="semester_type" id="semester_type" value="{{ old('semester_type', $collegeCalender->semester_type) }}">
                            @if($errors->has('semester_type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('semester_type') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.collegeCalender.fields.semester_type_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="from_date">{{ trans('cruds.collegeCalender.fields.from_date') }}</label>
                            <input class="form-control" type="text" name="from_date" id="from_date" value="{{ old('from_date', $collegeCalender->from_date) }}">
                            @if($errors->has('from_date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('from_date') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.collegeCalender.fields.from_date_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="to_date">{{ trans('cruds.collegeCalender.fields.to_date') }}</label>
                            <input class="form-control" type="text" name="to_date" id="to_date" value="{{ old('to_date', $collegeCalender->to_date) }}">
                            @if($errors->has('to_date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('to_date') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.collegeCalender.fields.to_date_helper') }}</span>
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