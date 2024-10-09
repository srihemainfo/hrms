@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.emailSetting.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.email-settings.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="host_name">{{ trans('cruds.emailSetting.fields.host_name') }}</label>
                <input class="form-control {{ $errors->has('host_name') ? 'is-invalid' : '' }}" type="text" name="host_name" id="host_name" value="{{ old('host_name', '') }}">
                @if($errors->has('host_name'))
                    <span class="text-danger">{{ $errors->first('host_name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.emailSetting.fields.host_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="user_name">{{ trans('cruds.emailSetting.fields.user_name') }}</label>
                <input class="form-control {{ $errors->has('user_name') ? 'is-invalid' : '' }}" type="text" name="user_name" id="user_name" value="{{ old('user_name', '') }}">
                @if($errors->has('user_name'))
                    <span class="text-danger">{{ $errors->first('user_name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.emailSetting.fields.user_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="password">{{ trans('cruds.emailSetting.fields.password') }}</label>
                <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="text" name="password" id="password" value="{{ old('password', '') }}">
                @if($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.emailSetting.fields.password_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="smtp_secure">{{ trans('cruds.emailSetting.fields.smtp_secure') }}</label>
                <input class="form-control {{ $errors->has('smtp_secure') ? 'is-invalid' : '' }}" type="text" name="smtp_secure" id="smtp_secure" value="{{ old('smtp_secure', '') }}">
                @if($errors->has('smtp_secure'))
                    <span class="text-danger">{{ $errors->first('smtp_secure') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.emailSetting.fields.smtp_secure_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="port_no">{{ trans('cruds.emailSetting.fields.port_no') }}</label>
                <input class="form-control {{ $errors->has('port_no') ? 'is-invalid' : '' }}" type="text" name="port_no" id="port_no" value="{{ old('port_no', '') }}">
                @if($errors->has('port_no'))
                    <span class="text-danger">{{ $errors->first('port_no') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.emailSetting.fields.port_no_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="from">{{ trans('cruds.emailSetting.fields.from') }}</label>
                <input class="form-control {{ $errors->has('from') ? 'is-invalid' : '' }}" type="text" name="from" id="from" value="{{ old('from', '') }}">
                @if($errors->has('from'))
                    <span class="text-danger">{{ $errors->first('from') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.emailSetting.fields.from_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="to">{{ trans('cruds.emailSetting.fields.to') }}</label>
                <input class="form-control {{ $errors->has('to') ? 'is-invalid' : '' }}" type="text" name="to" id="to" value="{{ old('to', '') }}">
                @if($errors->has('to'))
                    <span class="text-danger">{{ $errors->first('to') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.emailSetting.fields.to_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="cc">{{ trans('cruds.emailSetting.fields.cc') }}</label>
                <input class="form-control {{ $errors->has('cc') ? 'is-invalid' : '' }}" type="text" name="cc" id="cc" value="{{ old('cc', '') }}">
                @if($errors->has('cc'))
                    <span class="text-danger">{{ $errors->first('cc') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.emailSetting.fields.cc_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="bcc">{{ trans('cruds.emailSetting.fields.bcc') }}</label>
                <input class="form-control {{ $errors->has('bcc') ? 'is-invalid' : '' }}" type="text" name="bcc" id="bcc" value="{{ old('bcc', '') }}">
                @if($errors->has('bcc'))
                    <span class="text-danger">{{ $errors->first('bcc') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.emailSetting.fields.bcc_helper') }}</span>
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