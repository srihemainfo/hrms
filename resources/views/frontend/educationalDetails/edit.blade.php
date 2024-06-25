@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.educationalDetail.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.educational-details.update", [$educationalDetail->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="education_type_id">{{ trans('cruds.educationalDetail.fields.education_type') }}</label>
                            <select class="form-control select2" name="education_type_id" id="education_type_id">
                                @foreach($education_types as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('education_type_id') ? old('education_type_id') : $educationalDetail->education_type->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('education_type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('education_type') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.educationalDetail.fields.education_type_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="institute_name">{{ trans('cruds.educationalDetail.fields.institute_name') }}</label>
                            <input class="form-control" type="text" name="institute_name" id="institute_name" value="{{ old('institute_name', $educationalDetail->institute_name) }}">
                            @if($errors->has('institute_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('institute_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.educationalDetail.fields.institute_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="institute_location">{{ trans('cruds.educationalDetail.fields.institute_location') }}</label>
                            <input class="form-control" type="text" name="institute_location" id="institute_location" value="{{ old('institute_location', $educationalDetail->institute_location) }}">
                            @if($errors->has('institute_location'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('institute_location') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.educationalDetail.fields.institute_location_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="medium_id">{{ trans('cruds.educationalDetail.fields.medium') }}</label>
                            <select class="form-control select2" name="medium_id" id="medium_id">
                                @foreach($media as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('medium_id') ? old('medium_id') : $educationalDetail->medium->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('medium'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('medium') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.educationalDetail.fields.medium_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="board_or_university">{{ trans('cruds.educationalDetail.fields.board_or_university') }}</label>
                            <input class="form-control" type="text" name="board_or_university" id="board_or_university" value="{{ old('board_or_university', $educationalDetail->board_or_university) }}">
                            @if($errors->has('board_or_university'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('board_or_university') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.educationalDetail.fields.board_or_university_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="marks">{{ trans('cruds.educationalDetail.fields.marks') }}</label>
                            <input class="form-control" type="text" name="marks" id="marks" value="{{ old('marks', $educationalDetail->marks) }}">
                            @if($errors->has('marks'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('marks') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.educationalDetail.fields.marks_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="marks_in_percentage">{{ trans('cruds.educationalDetail.fields.marks_in_percentage') }}</label>
                            <input class="form-control" type="text" name="marks_in_percentage" id="marks_in_percentage" value="{{ old('marks_in_percentage', $educationalDetail->marks_in_percentage) }}">
                            @if($errors->has('marks_in_percentage'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('marks_in_percentage') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.educationalDetail.fields.marks_in_percentage_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="subject_1">{{ trans('cruds.educationalDetail.fields.subject_1') }}</label>
                            <input class="form-control" type="text" name="subject_1" id="subject_1" value="{{ old('subject_1', $educationalDetail->subject_1) }}">
                            @if($errors->has('subject_1'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('subject_1') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.educationalDetail.fields.subject_1_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="mark_1">{{ trans('cruds.educationalDetail.fields.mark_1') }}</label>
                            <input class="form-control" type="text" name="mark_1" id="mark_1" value="{{ old('mark_1', $educationalDetail->mark_1) }}">
                            @if($errors->has('mark_1'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mark_1') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.educationalDetail.fields.mark_1_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="subject_2">{{ trans('cruds.educationalDetail.fields.subject_2') }}</label>
                            <input class="form-control" type="text" name="subject_2" id="subject_2" value="{{ old('subject_2', $educationalDetail->subject_2) }}">
                            @if($errors->has('subject_2'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('subject_2') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.educationalDetail.fields.subject_2_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="mark_2">{{ trans('cruds.educationalDetail.fields.mark_2') }}</label>
                            <input class="form-control" type="text" name="mark_2" id="mark_2" value="{{ old('mark_2', $educationalDetail->mark_2) }}">
                            @if($errors->has('mark_2'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mark_2') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.educationalDetail.fields.mark_2_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="subject_3">{{ trans('cruds.educationalDetail.fields.subject_3') }}</label>
                            <input class="form-control" type="text" name="subject_3" id="subject_3" value="{{ old('subject_3', $educationalDetail->subject_3) }}">
                            @if($errors->has('subject_3'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('subject_3') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.educationalDetail.fields.subject_3_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="mark_3">{{ trans('cruds.educationalDetail.fields.mark_3') }}</label>
                            <input class="form-control" type="text" name="mark_3" id="mark_3" value="{{ old('mark_3', $educationalDetail->mark_3) }}">
                            @if($errors->has('mark_3'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mark_3') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.educationalDetail.fields.mark_3_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="subject_4">{{ trans('cruds.educationalDetail.fields.subject_4') }}</label>
                            <input class="form-control" type="text" name="subject_4" id="subject_4" value="{{ old('subject_4', $educationalDetail->subject_4) }}">
                            @if($errors->has('subject_4'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('subject_4') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.educationalDetail.fields.subject_4_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="mark_4">{{ trans('cruds.educationalDetail.fields.mark_4') }}</label>
                            <input class="form-control" type="text" name="mark_4" id="mark_4" value="{{ old('mark_4', $educationalDetail->mark_4) }}">
                            @if($errors->has('mark_4'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mark_4') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.educationalDetail.fields.mark_4_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="subject_5">{{ trans('cruds.educationalDetail.fields.subject_5') }}</label>
                            <input class="form-control" type="text" name="subject_5" id="subject_5" value="{{ old('subject_5', $educationalDetail->subject_5) }}">
                            @if($errors->has('subject_5'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('subject_5') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.educationalDetail.fields.subject_5_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="mark_5">{{ trans('cruds.educationalDetail.fields.mark_5') }}</label>
                            <input class="form-control" type="text" name="mark_5" id="mark_5" value="{{ old('mark_5', $educationalDetail->mark_5) }}">
                            @if($errors->has('mark_5'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mark_5') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.educationalDetail.fields.mark_5_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="subject_6">{{ trans('cruds.educationalDetail.fields.subject_6') }}</label>
                            <input class="form-control" type="text" name="subject_6" id="subject_6" value="{{ old('subject_6', $educationalDetail->subject_6) }}">
                            @if($errors->has('subject_6'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('subject_6') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.educationalDetail.fields.subject_6_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="mark_6">{{ trans('cruds.educationalDetail.fields.mark_6') }}</label>
                            <input class="form-control" type="text" name="mark_6" id="mark_6" value="{{ old('mark_6', $educationalDetail->mark_6) }}">
                            @if($errors->has('mark_6'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mark_6') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.educationalDetail.fields.mark_6_helper') }}</span>
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