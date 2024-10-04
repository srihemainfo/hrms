@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.hrmRequestPermission.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.hrm-request-permissions.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="user_id">{{ trans('cruds.hrmRequestPermission.fields.user') }}</label>
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
                            <span class="help-block">{{ trans('cruds.hrmRequestPermission.fields.user_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="no_of_hours">{{ trans('cruds.hrmRequestPermission.fields.no_of_hours') }}</label>
                            <input class="form-control" type="text" name="no_of_hours" id="no_of_hours" value="{{ old('no_of_hours', '') }}">
                            @if($errors->has('no_of_hours'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('no_of_hours') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.hrmRequestPermission.fields.no_of_hours_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="from_date">{{ trans('cruds.hrmRequestPermission.fields.from_date') }}</label>
                            <input class="form-control" type="text" name="from_date" id="from_date" value="{{ old('from_date', '') }}">
                            @if($errors->has('from_date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('from_date') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.hrmRequestPermission.fields.from_date_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="reason">{{ trans('cruds.hrmRequestPermission.fields.reason') }}</label>
                            <input class="form-control" type="text" name="reason" id="reason" value="{{ old('reason', '') }}">
                            @if($errors->has('reason'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('reason') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.hrmRequestPermission.fields.reason_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="approved_by">{{ trans('cruds.hrmRequestPermission.fields.approved_by') }}</label>
                            <input class="form-control" type="text" name="approved_by" id="approved_by" value="{{ old('approved_by', '') }}">
                            @if($errors->has('approved_by'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('approved_by') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection