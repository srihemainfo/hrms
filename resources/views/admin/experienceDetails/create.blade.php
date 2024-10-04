@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.experienceDetail.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.experience-details.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="designation">{{ trans('cruds.experienceDetail.fields.designation') }}</label>
                <input class="form-control {{ $errors->has('designation') ? 'is-invalid' : '' }}" type="text" name="designation" id="designation" value="{{ old('designation', '') }}">
                @if($errors->has('designation'))
                    <span class="text-danger">{{ $errors->first('designation') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.experienceDetail.fields.designation_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="years_of_experience">{{ trans('cruds.experienceDetail.fields.years_of_experience') }}</label>
                <input class="form-control {{ $errors->has('years_of_experience') ? 'is-invalid' : '' }}" type="number" name="years_of_experience" id="years_of_experience" value="{{ old('years_of_experience', '') }}" step="1">
                @if($errors->has('years_of_experience'))
                    <span class="text-danger">{{ $errors->first('years_of_experience') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.experienceDetail.fields.years_of_experience_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="worked_place">{{ trans('cruds.experienceDetail.fields.worked_place') }}</label>
                <input class="form-control {{ $errors->has('worked_place') ? 'is-invalid' : '' }}" type="text" name="worked_place" id="worked_place" value="{{ old('worked_place', '') }}">
                @if($errors->has('worked_place'))
                    <span class="text-danger">{{ $errors->first('worked_place') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.experienceDetail.fields.worked_place_helper') }}</span>
            </div>
            {{-- <div class="form-group">
                <label for="taken_subjects">{{ trans('cruds.experienceDetail.fields.taken_subjects') }}</label>
                <input class="form-control {{ $errors->has('taken_subjects') ? 'is-invalid' : '' }}" type="text" name="taken_subjects" id="taken_subjects" value="{{ old('taken_subjects', '') }}">
                @if($errors->has('taken_subjects'))
                    <span class="text-danger">{{ $errors->first('taken_subjects') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.experienceDetail.fields.taken_subjects_helper') }}</span>
            </div> --}}
            <div class="form-group">
                <label class="required" for="from_date">{{ trans('cruds.experienceDetail.fields.from_date') }}</label>
                <input class="form-control date {{ $errors->has('from_date') ? 'is-invalid' : '' }}" type="text" name="from_date" id="from_date" value="{{ old('from_date') }}" required>
                @if($errors->has('from_date'))
                    <span class="text-danger">{{ $errors->first('from_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.experienceDetail.fields.from_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="to_date">{{ trans('cruds.experienceDetail.fields.to_date') }}</label>
                <input class="form-control date {{ $errors->has('to_date') ? 'is-invalid' : '' }}" type="text" name="to_date" id="to_date" value="{{ old('to_date') }}" required>
                @if($errors->has('to_date'))
                    <span class="text-danger">{{ $errors->first('to_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.experienceDetail.fields.to_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="name_id">{{ trans('cruds.experienceDetail.fields.name') }}</label>
                <select class="form-control select2 {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name_id" id="name_id">
                    @foreach($names as $id => $entry)
                        <option value="{{ $id }}" {{ old('name_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
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



@endsection
@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.experienceDetail.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.experience-details.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="designation">{{ trans('cruds.experienceDetail.fields.designation') }}</label>
                <input class="form-control {{ $errors->has('designation') ? 'is-invalid' : '' }}" type="text" name="designation" id="designation" value="{{ old('designation', '') }}">
                @if($errors->has('designation'))
                    <span class="text-danger">{{ $errors->first('designation') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.experienceDetail.fields.designation_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="years_of_experience">{{ trans('cruds.experienceDetail.fields.years_of_experience') }}</label>
                <input class="form-control {{ $errors->has('years_of_experience') ? 'is-invalid' : '' }}" type="number" name="years_of_experience" id="years_of_experience" value="{{ old('years_of_experience', '') }}" step="1">
                @if($errors->has('years_of_experience'))
                    <span class="text-danger">{{ $errors->first('years_of_experience') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.experienceDetail.fields.years_of_experience_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="worked_place">{{ trans('cruds.experienceDetail.fields.worked_place') }}</label>
                <input class="form-control {{ $errors->has('worked_place') ? 'is-invalid' : '' }}" type="text" name="worked_place" id="worked_place" value="{{ old('worked_place', '') }}">
                @if($errors->has('worked_place'))
                    <span class="text-danger">{{ $errors->first('worked_place') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.experienceDetail.fields.worked_place_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="taken_subjects">{{ trans('cruds.experienceDetail.fields.taken_subjects') }}</label>
                <input class="form-control {{ $errors->has('taken_subjects') ? 'is-invalid' : '' }}" type="text" name="taken_subjects" id="taken_subjects" value="{{ old('taken_subjects', '') }}">
                @if($errors->has('taken_subjects'))
                    <span class="text-danger">{{ $errors->first('taken_subjects') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.experienceDetail.fields.taken_subjects_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="from_date">{{ trans('cruds.experienceDetail.fields.from_date') }}</label>
                <input class="form-control date {{ $errors->has('from_date') ? 'is-invalid' : '' }}" type="text" name="from_date" id="from_date" value="{{ old('from_date') }}" required>
                @if($errors->has('from_date'))
                    <span class="text-danger">{{ $errors->first('from_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.experienceDetail.fields.from_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="to_date">{{ trans('cruds.experienceDetail.fields.to_date') }}</label>
                <input class="form-control date {{ $errors->has('to_date') ? 'is-invalid' : '' }}" type="text" name="to_date" id="to_date" value="{{ old('to_date') }}" required>
                @if($errors->has('to_date'))
                    <span class="text-danger">{{ $errors->first('to_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.experienceDetail.fields.to_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="name_id">{{ trans('cruds.experienceDetail.fields.name') }}</label>
                <select class="form-control select2 {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name_id" id="name_id">
                    @foreach($names as $id => $entry)
                        <option value="{{ $id }}" {{ old('name_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
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



@endsection