@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.hrmRequestPermission.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.hrm-request-permissions.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="user_id">{{ trans('cruds.hrmRequestPermission.fields.user') }}</label>
                <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id" required>
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <span class="text-danger">{{ $errors->first('user') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.hrmRequestPermission.fields.user_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="no_of_hours">{{ trans('cruds.hrmRequestPermission.fields.no_of_hours') }}</label>
                <input class="form-control {{ $errors->has('no_of_hours') ? 'is-invalid' : '' }}" type="text" name="no_of_hours" id="no_of_hours" value="{{ old('no_of_hours', '') }}">
                @if($errors->has('no_of_hours'))
                    <span class="text-danger">{{ $errors->first('no_of_hours') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.hrmRequestPermission.fields.no_of_hours_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="from_date">{{ trans('cruds.hrmRequestPermission.fields.from_date') }}</label>
                <input class="form-control {{ $errors->has('from_date') ? 'is-invalid' : '' }}" type="text" name="from_date" id="from_date" value="{{ old('from_date', '') }}">
                @if($errors->has('from_date'))
                    <span class="text-danger">{{ $errors->first('from_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.hrmRequestPermission.fields.from_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="reason">{{ trans('cruds.hrmRequestPermission.fields.reason') }}</label>
                <input class="form-control {{ $errors->has('reason') ? 'is-invalid' : '' }}" type="text" name="reason" id="reason" value="{{ old('reason', '') }}">
                @if($errors->has('reason'))
                    <span class="text-danger">{{ $errors->first('reason') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.hrmRequestPermission.fields.reason_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="approved_by">{{ trans('cruds.hrmRequestPermission.fields.approved_by') }}</label>
                <input class="form-control {{ $errors->has('approved_by') ? 'is-invalid' : '' }}" type="text" name="approved_by" id="approved_by" value="{{ old('approved_by', '') }}">
                @if($errors->has('approved_by'))
                    <span class="text-danger">{{ $errors->first('approved_by') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.hrmRequestPermission.fields.approved_by_helper') }}</span>
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