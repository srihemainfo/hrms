@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.academicDetail.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.academic-details.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label for="enroll_master_number_id">{{ trans('cruds.academicDetail.fields.enroll_master_number') }}</label>
                            <select class="form-control select2" name="enroll_master_number_id" id="enroll_master_number_id">
                                @foreach($enroll_master_numbers as $id => $entry)
                                    <option value="{{ $id }}" {{ old('enroll_master_number_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('enroll_master_number'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('enroll_master_number') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.academicDetail.fields.enroll_master_number_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="register_number">{{ trans('cruds.academicDetail.fields.register_number') }}</label>
                            <input class="form-control" type="text" name="register_number" id="register_number" value="{{ old('register_number', '') }}">
                            @if($errors->has('register_number'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('register_number') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.academicDetail.fields.register_number_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="emis_number">{{ trans('cruds.academicDetail.fields.emis_number') }}</label>
                            <input class="form-control" type="text" name="emis_number" id="emis_number" value="{{ old('emis_number', '') }}">
                            @if($errors->has('emis_number'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('emis_number') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection