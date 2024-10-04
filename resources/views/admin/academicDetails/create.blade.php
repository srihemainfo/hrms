@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.academicDetail.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.academic-details.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="enroll_master_number_id">{{ trans('cruds.academicDetail.fields.enroll_master_number') }}</label>
                <select class="form-control select2 {{ $errors->has('enroll_master_number') ? 'is-invalid' : '' }}" name="enroll_master_number_id" id="enroll_master_number_id">
                    @foreach($enroll_master_numbers as $id => $entry)
                        <option value="{{ $id }}" {{ old('enroll_master_number_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('enroll_master_number'))
                    <span class="text-danger">{{ $errors->first('enroll_master_number') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.academicDetail.fields.enroll_master_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="register_number">{{ trans('cruds.academicDetail.fields.register_number') }}</label>
                <input class="form-control {{ $errors->has('register_number') ? 'is-invalid' : '' }}" type="text" name="register_number" id="register_number" value="{{ old('register_number', '') }}">
                @if($errors->has('register_number'))
                    <span class="text-danger">{{ $errors->first('register_number') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.academicDetail.fields.register_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="emis_number">{{ trans('cruds.academicDetail.fields.emis_number') }}</label>
                <input class="form-control {{ $errors->has('emis_number') ? 'is-invalid' : '' }}" type="text" name="emis_number" id="emis_number" value="{{ old('emis_number', '') }}">
                @if($errors->has('emis_number'))
                    <span class="text-danger">{{ $errors->first('emis_number') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.academicDetail.fields.emis_number_helper') }}</span>
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
