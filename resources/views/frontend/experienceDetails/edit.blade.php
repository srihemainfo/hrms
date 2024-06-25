@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.experienceDetail.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.experience-details.update", [$experienceDetail->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="designation">{{ trans('cruds.experienceDetail.fields.designation') }}</label>
                            <input class="form-control" type="text" name="designation" id="designation" value="{{ old('designation', $experienceDetail->designation) }}">
                            @if($errors->has('designation'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('designation') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.experienceDetail.fields.designation_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="years_of_experience">{{ trans('cruds.experienceDetail.fields.years_of_experience') }}</label>
                            <input class="form-control" type="number" name="years_of_experience" id="years_of_experience" value="{{ old('years_of_experience', $experienceDetail->years_of_experience) }}" step="1">
                            @if($errors->has('years_of_experience'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('years_of_experience') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.experienceDetail.fields.years_of_experience_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="worked_place">{{ trans('cruds.experienceDetail.fields.worked_place') }}</label>
                            <input class="form-control" type="text" name="worked_place" id="worked_place" value="{{ old('worked_place', $experienceDetail->worked_place) }}">
                            @if($errors->has('worked_place'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('worked_place') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.experienceDetail.fields.worked_place_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="taken_subjects">{{ trans('cruds.experienceDetail.fields.taken_subjects') }}</label>
                            <input class="form-control" type="text" name="taken_subjects" id="taken_subjects" value="{{ old('taken_subjects', $experienceDetail->taken_subjects) }}">
                            @if($errors->has('taken_subjects'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('taken_subjects') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.experienceDetail.fields.taken_subjects_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="from_date">{{ trans('cruds.experienceDetail.fields.from_date') }}</label>
                            <input class="form-control date" type="text" name="from_date" id="from_date" value="{{ old('from_date', $experienceDetail->from_date) }}" required>
                            @if($errors->has('from_date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('from_date') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.experienceDetail.fields.from_date_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="to_date">{{ trans('cruds.experienceDetail.fields.to_date') }}</label>
                            <input class="form-control date" type="text" name="to_date" id="to_date" value="{{ old('to_date', $experienceDetail->to_date) }}" required>
                            @if($errors->has('to_date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('to_date') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.experienceDetail.fields.to_date_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="name_id">{{ trans('cruds.experienceDetail.fields.name') }}</label>
                            <select class="form-control select2" name="name_id" id="name_id">
                                @foreach($names as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('name_id') ? old('name_id') : $experienceDetail->name->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.experienceDetail.fields.name_helper') }}</span>
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