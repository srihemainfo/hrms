@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.emailSetting.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.email-settings.update", [$emailSetting->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="host_name">{{ trans('cruds.emailSetting.fields.host_name') }}</label>
                            <input class="form-control" type="text" name="host_name" id="host_name" value="{{ old('host_name', $emailSetting->host_name) }}">
                            @if($errors->has('host_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('host_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.emailSetting.fields.host_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="user_name">{{ trans('cruds.emailSetting.fields.user_name') }}</label>
                            <input class="form-control" type="text" name="user_name" id="user_name" value="{{ old('user_name', $emailSetting->user_name) }}">
                            @if($errors->has('user_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.emailSetting.fields.user_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="password">{{ trans('cruds.emailSetting.fields.password') }}</label>
                            <input class="form-control" type="text" name="password" id="password" value="{{ old('password', $emailSetting->password) }}">
                            @if($errors->has('password'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('password') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.emailSetting.fields.password_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="smtp_secure">{{ trans('cruds.emailSetting.fields.smtp_secure') }}</label>
                            <input class="form-control" type="text" name="smtp_secure" id="smtp_secure" value="{{ old('smtp_secure', $emailSetting->smtp_secure) }}">
                            @if($errors->has('smtp_secure'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('smtp_secure') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.emailSetting.fields.smtp_secure_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="port_no">{{ trans('cruds.emailSetting.fields.port_no') }}</label>
                            <input class="form-control" type="text" name="port_no" id="port_no" value="{{ old('port_no', $emailSetting->port_no) }}">
                            @if($errors->has('port_no'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('port_no') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.emailSetting.fields.port_no_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="from">{{ trans('cruds.emailSetting.fields.from') }}</label>
                            <input class="form-control" type="text" name="from" id="from" value="{{ old('from', $emailSetting->from) }}">
                            @if($errors->has('from'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('from') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.emailSetting.fields.from_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="to">{{ trans('cruds.emailSetting.fields.to') }}</label>
                            <input class="form-control" type="text" name="to" id="to" value="{{ old('to', $emailSetting->to) }}">
                            @if($errors->has('to'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('to') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.emailSetting.fields.to_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="cc">{{ trans('cruds.emailSetting.fields.cc') }}</label>
                            <input class="form-control" type="text" name="cc" id="cc" value="{{ old('cc', $emailSetting->cc) }}">
                            @if($errors->has('cc'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('cc') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.emailSetting.fields.cc_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="bcc">{{ trans('cruds.emailSetting.fields.bcc') }}</label>
                            <input class="form-control" type="text" name="bcc" id="bcc" value="{{ old('bcc', $emailSetting->bcc) }}">
                            @if($errors->has('bcc'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('bcc') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection