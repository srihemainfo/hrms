@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.teachingStaff.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.teaching-staffs.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label for="name">{{ trans('cruds.teachingStaff.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', '') }}">
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.teachingStaff.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="subject_id">{{ trans('cruds.teachingStaff.fields.subject') }}</label>
                            <select class="form-control select2" name="subject_id" id="subject_id">
                                @foreach($subjects as $id => $entry)
                                    <option value="{{ $id }}" {{ old('subject_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('subject'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('subject') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.teachingStaff.fields.subject_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="enroll_master_id">{{ trans('cruds.teachingStaff.fields.enroll_master') }}</label>
                            <select class="form-control select2" name="enroll_master_id" id="enroll_master_id">
                                @foreach($enroll_masters as $id => $entry)
                                    <option value="{{ $id }}" {{ old('enroll_master_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('enroll_master'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('enroll_master') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.teachingStaff.fields.enroll_master_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="working_as_id">{{ trans('cruds.teachingStaff.fields.working_as') }}</label>
                            <select class="form-control select2" name="working_as_id" id="working_as_id">
                                @foreach($working_as as $id => $entry)
                                    <option value="{{ $id }}" {{ old('working_as_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('working_as'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('working_as') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.teachingStaff.fields.working_as_helper') }}</span>
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