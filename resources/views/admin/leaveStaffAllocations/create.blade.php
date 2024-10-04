@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.leaveStaffAllocation.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.leave-staff-allocations.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="user_id">{{ trans('cruds.leaveStaffAllocation.fields.user') }}</label>
                <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id" required>
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <span class="text-danger">{{ $errors->first('user') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.leaveStaffAllocation.fields.user_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="academic_year_id">{{ trans('cruds.leaveStaffAllocation.fields.academic_year') }}</label>
                <select class="form-control select2 {{ $errors->has('academic_year') ? 'is-invalid' : '' }}" name="academic_year_id" id="academic_year_id" required>
                    @foreach($academic_years as $id => $entry)
                        <option value="{{ $id }}" {{ old('academic_year_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('academic_year'))
                    <span class="text-danger">{{ $errors->first('academic_year') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.leaveStaffAllocation.fields.academic_year_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="no_of_leave">{{ trans('cruds.leaveStaffAllocation.fields.no_of_leave') }}</label>
                <input class="form-control {{ $errors->has('no_of_leave') ? 'is-invalid' : '' }}" type="number" name="no_of_leave" id="no_of_leave" value="{{ old('no_of_leave', '') }}" step="1" required>
                @if($errors->has('no_of_leave'))
                    <span class="text-danger">{{ $errors->first('no_of_leave') }}</span>
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



@endsection