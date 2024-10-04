@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.smsTemplate.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.sms-templates.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label for="name">{{ trans('cruds.smsTemplate.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', '') }}">
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.smsTemplate.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="sender">{{ trans('cruds.smsTemplate.fields.sender') }}</label>
                            <input class="form-control" type="text" name="sender" id="sender" value="{{ old('sender', '') }}">
                            @if($errors->has('sender'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('sender') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.smsTemplate.fields.sender_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="template">{{ trans('cruds.smsTemplate.fields.template') }}</label>
                            <input class="form-control" type="text" name="template" id="template" value="{{ old('template', '') }}">
                            @if($errors->has('template'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('template') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.smsTemplate.fields.template_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="type">{{ trans('cruds.smsTemplate.fields.type') }}</label>
                            <input class="form-control" type="text" name="type" id="type" value="{{ old('type', '') }}">
                            @if($errors->has('type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('type') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.smsTemplate.fields.type_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="content">{{ trans('cruds.smsTemplate.fields.content') }}</label>
                            <input class="form-control" type="text" name="content" id="content" value="{{ old('content', '') }}">
                            @if($errors->has('content'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('content') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.smsTemplate.fields.content_helper') }}</span>
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