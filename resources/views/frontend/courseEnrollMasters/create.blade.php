@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.courseEnrollMaster.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.course-enroll-masters.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="enroll_master_number">{{ trans('cruds.courseEnrollMaster.fields.enroll_master_number') }}</label>
                            <input class="form-control" type="text" name="enroll_master_number" id="enroll_master_number" value="{{ old('enroll_master_number', '') }}" required>
                            @if($errors->has('enroll_master_number'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('enroll_master_number') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.courseEnrollMaster.fields.enroll_master_number_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="degreetype_id">{{ trans('cruds.courseEnrollMaster.fields.degreetype') }}</label>
                            <select class="form-control select2" name="degreetype_id" id="degreetype_id" required>
                                @foreach($degreetypes as $id => $entry)
                                    <option value="{{ $id }}" {{ old('degreetype_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('degreetype'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('degreetype') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.courseEnrollMaster.fields.degreetype_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="batch_id">{{ trans('cruds.courseEnrollMaster.fields.batch') }}</label>
                            <select class="form-control select2" name="batch_id" id="batch_id" required>
                                @foreach($batches as $id => $entry)
                                    <option value="{{ $id }}" {{ old('batch_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('batch'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('batch') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.courseEnrollMaster.fields.batch_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="academic_id">{{ trans('cruds.courseEnrollMaster.fields.academic') }}</label>
                            <select class="form-control select2" name="academic_id" id="academic_id">
                                @foreach($academics as $id => $entry)
                                    <option value="{{ $id }}" {{ old('academic_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('academic'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('academic') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.courseEnrollMaster.fields.academic_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="course_id">{{ trans('cruds.courseEnrollMaster.fields.course') }}</label>
                            <select class="form-control select2" name="course_id" id="course_id" required>
                                @foreach($courses as $id => $entry)
                                    <option value="{{ $id }}" {{ old('course_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('course'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('course') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.courseEnrollMaster.fields.course_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="department_id">{{ trans('cruds.courseEnrollMaster.fields.department') }}</label>
                            <select class="form-control select2" name="department_id" id="department_id" required>
                                @foreach($departments as $id => $entry)
                                    <option value="{{ $id }}" {{ old('department_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('department'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('department') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.courseEnrollMaster.fields.department_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="semester_id">{{ trans('cruds.courseEnrollMaster.fields.semester') }}</label>
                            <select class="form-control select2" name="semester_id" id="semester_id" required>
                                @foreach($semesters as $id => $entry)
                                    <option value="{{ $id }}" {{ old('semester_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('semester'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('semester') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.courseEnrollMaster.fields.semester_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="section_id">{{ trans('cruds.courseEnrollMaster.fields.section') }}</label>
                            <select class="form-control select2" name="section_id" id="section_id" required>
                                @foreach($sections as $id => $entry)
                                    <option value="{{ $id }}" {{ old('section_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('section'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('section') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.courseEnrollMaster.fields.section_helper') }}</span>
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