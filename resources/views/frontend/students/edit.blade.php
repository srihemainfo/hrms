@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.student.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.students.update", [$student->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="name">{{ trans('cruds.student.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $student->name) }}" required>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.student.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="reg_no">{{ trans('cruds.student.fields.reg_no') }}</label>
                            <input class="form-control" type="number" name="reg_no" id="reg_no" value="{{ old('reg_no', $student->reg_no) }}" step="1" required>
                            @if($errors->has('reg_no'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('reg_no') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.student.fields.reg_no_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="enroll_master_id">{{ trans('cruds.student.fields.enroll_master') }}</label>
                            <select class="form-control select2" name="enroll_master_id" id="enroll_master_id">
                                @foreach($enroll_masters as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('enroll_master_id') ? old('enroll_master_id') : $student->enroll_master->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('enroll_master'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('enroll_master') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.student.fields.enroll_master_helper') }}</span>
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