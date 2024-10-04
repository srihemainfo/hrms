@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.paymentGateway.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.payment-gateways.update", [$paymentGateway->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="gateway_type">{{ trans('cruds.paymentGateway.fields.gateway_type') }}</label>
                <input class="form-control {{ $errors->has('gateway_type') ? 'is-invalid' : '' }}" type="text" name="gateway_type" id="gateway_type" value="{{ old('gateway_type', $paymentGateway->gateway_type) }}">
                @if($errors->has('gateway_type'))
                    <span class="text-danger">{{ $errors->first('gateway_type') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.paymentGateway.fields.gateway_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="prefix">{{ trans('cruds.paymentGateway.fields.prefix') }}</label>
                <input class="form-control {{ $errors->has('prefix') ? 'is-invalid' : '' }}" type="text" name="prefix" id="prefix" value="{{ old('prefix', $paymentGateway->prefix) }}">
                @if($errors->has('prefix'))
                    <span class="text-danger">{{ $errors->first('prefix') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.paymentGateway.fields.prefix_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="url">{{ trans('cruds.paymentGateway.fields.url') }}</label>
                <input class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" type="text" name="url" id="url" value="{{ old('url', $paymentGateway->url) }}">
                @if($errors->has('url'))
                    <span class="text-danger">{{ $errors->first('url') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.paymentGateway.fields.url_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="username">{{ trans('cruds.paymentGateway.fields.username') }}</label>
                <input class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" type="text" name="username" id="username" value="{{ old('username', $paymentGateway->username) }}">
                @if($errors->has('username'))
                    <span class="text-danger">{{ $errors->first('username') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.paymentGateway.fields.username_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="password">{{ trans('cruds.paymentGateway.fields.password') }}</label>
                <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="text" name="password" id="password" value="{{ old('password', $paymentGateway->password) }}">
                @if($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.paymentGateway.fields.password_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="merchand">{{ trans('cruds.paymentGateway.fields.merchand') }}</label>
                <input class="form-control {{ $errors->has('merchand') ? 'is-invalid' : '' }}" type="text" name="merchand" id="merchand" value="{{ old('merchand', $paymentGateway->merchand) }}">
                @if($errors->has('merchand'))
                    <span class="text-danger">{{ $errors->first('merchand') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.paymentGateway.fields.merchand_helper') }}</span>
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