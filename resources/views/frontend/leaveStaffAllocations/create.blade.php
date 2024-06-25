@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.leaveStaffAllocation.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.leave-staff-allocations.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="user_id">{{ trans('cruds.leaveStaffAllocation.fields.user') }}</label>
                            <select class="form-control select2" name="user_id" id="user_id" required>
                                @foreach($users as $id => $entry)
                                    <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('user'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.leaveStaffAllocation.fields.user_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="academic_year_id">{{ trans('cruds.leaveStaffAllocation.fields.academic_year') }}</label>
                            <select class="form-control select2" name="academic_year_id" id="academic_year_id" required>
                                @foreach($academic_years as $id => $entry)
                                    <option value="{{ $id }}" {{ old('academic_year_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('academic_year'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('academic_year') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.leaveStaffAllocation.fields.academic_year_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="no_of_leave">{{ trans('cruds.leaveStaffAllocation.fields.no_of_leave') }}</label>
                            <input class="form-control" type="number" name="no_of_leave" id="no_of_leave" value="{{ old('no_of_leave', '') }}" step="1" required>
                            @if($errors->has('no_of_leave'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('no_of_leave') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.leaveStaffAllocation.fields.no_of_leave_helper') }}</span>
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