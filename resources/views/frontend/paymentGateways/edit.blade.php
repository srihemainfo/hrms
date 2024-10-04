@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.paymentGateway.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.payment-gateways.update", [$paymentGateway->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="gateway_type">{{ trans('cruds.paymentGateway.fields.gateway_type') }}</label>
                            <input class="form-control" type="text" name="gateway_type" id="gateway_type" value="{{ old('gateway_type', $paymentGateway->gateway_type) }}">
                            @if($errors->has('gateway_type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('gateway_type') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.paymentGateway.fields.gateway_type_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="prefix">{{ trans('cruds.paymentGateway.fields.prefix') }}</label>
                            <input class="form-control" type="text" name="prefix" id="prefix" value="{{ old('prefix', $paymentGateway->prefix) }}">
                            @if($errors->has('prefix'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('prefix') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.paymentGateway.fields.prefix_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="url">{{ trans('cruds.paymentGateway.fields.url') }}</label>
                            <input class="form-control" type="text" name="url" id="url" value="{{ old('url', $paymentGateway->url) }}">
                            @if($errors->has('url'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('url') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.paymentGateway.fields.url_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="username">{{ trans('cruds.paymentGateway.fields.username') }}</label>
                            <input class="form-control" type="text" name="username" id="username" value="{{ old('username', $paymentGateway->username) }}">
                            @if($errors->has('username'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('username') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.paymentGateway.fields.username_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="password">{{ trans('cruds.paymentGateway.fields.password') }}</label>
                            <input class="form-control" type="text" name="password" id="password" value="{{ old('password', $paymentGateway->password) }}">
                            @if($errors->has('password'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('password') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.paymentGateway.fields.password_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="merchand">{{ trans('cruds.paymentGateway.fields.merchand') }}</label>
                            <input class="form-control" type="text" name="merchand" id="merchand" value="{{ old('merchand', $paymentGateway->merchand) }}">
                            @if($errors->has('merchand'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('merchand') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection