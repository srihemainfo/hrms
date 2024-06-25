@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.smsTemplate.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.sms-templates.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">{{ trans('cruds.smsTemplate.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}">
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.smsTemplate.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="sender">{{ trans('cruds.smsTemplate.fields.sender') }}</label>
                <input class="form-control {{ $errors->has('sender') ? 'is-invalid' : '' }}" type="text" name="sender" id="sender" value="{{ old('sender', '') }}">
                @if($errors->has('sender'))
                    <span class="text-danger">{{ $errors->first('sender') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.smsTemplate.fields.sender_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="template">{{ trans('cruds.smsTemplate.fields.template') }}</label>
                <input class="form-control {{ $errors->has('template') ? 'is-invalid' : '' }}" type="text" name="template" id="template" value="{{ old('template', '') }}">
                @if($errors->has('template'))
                    <span class="text-danger">{{ $errors->first('template') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.smsTemplate.fields.template_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="type">{{ trans('cruds.smsTemplate.fields.type') }}</label>
                <input class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" type="text" name="type" id="type" value="{{ old('type', '') }}">
                @if($errors->has('type'))
                    <span class="text-danger">{{ $errors->first('type') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.smsTemplate.fields.type_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="content">{{ trans('cruds.smsTemplate.fields.content') }}</label>
                <input class="form-control {{ $errors->has('content') ? 'is-invalid' : '' }}" type="text" name="content" id="content" value="{{ old('content', '') }}">
                @if($errors->has('content'))
                    <span class="text-danger">{{ $errors->first('content') }}</span>
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



@endsection