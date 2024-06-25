@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.sponser.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.sponsers.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="user_name_id">{{ trans('cruds.sponser.fields.user_name') }}</label>
                <select class="form-control select2 {{ $errors->has('user_name') ? 'is-invalid' : '' }}" name="user_name_id" id="user_name_id">
                    @foreach($user_names as $id => $entry)
                        <option value="{{ $id }}" {{ old('user_name_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user_name'))
                    <span class="text-danger">{{ $errors->first('user_name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sponser.fields.user_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="sponser_type">{{ trans('cruds.sponser.fields.sponser_type') }}</label>
                <input class="form-control {{ $errors->has('sponser_type') ? 'is-invalid' : '' }}" type="text" name="sponser_type" id="sponser_type" value="{{ old('sponser_type', '') }}">
                @if($errors->has('sponser_type'))
                    <span class="text-danger">{{ $errors->first('sponser_type') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sponser.fields.sponser_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="sponser_name">{{ trans('cruds.sponser.fields.sponser_name') }}</label>
                <input class="form-control {{ $errors->has('sponser_name') ? 'is-invalid' : '' }}" type="text" name="sponser_name" id="sponser_name" value="{{ old('sponser_name', '') }}">
                @if($errors->has('sponser_name'))
                    <span class="text-danger">{{ $errors->first('sponser_name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sponser.fields.sponser_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="sponsered_items">{{ trans('cruds.sponser.fields.sponsered_items') }}</label>
                <input class="form-control {{ $errors->has('sponsered_items') ? 'is-invalid' : '' }}" type="text" name="sponsered_items" id="sponsered_items" value="{{ old('sponsered_items', '') }}">
                @if($errors->has('sponsered_items'))
                    <span class="text-danger">{{ $errors->first('sponsered_items') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sponser.fields.sponsered_items_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="sponsered_to">{{ trans('cruds.sponser.fields.sponsered_to') }}</label>
                <input class="form-control {{ $errors->has('sponsered_to') ? 'is-invalid' : '' }}" type="text" name="sponsered_to" id="sponsered_to" value="{{ old('sponsered_to', '') }}">
                @if($errors->has('sponsered_to'))
                    <span class="text-danger">{{ $errors->first('sponsered_to') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sponser.fields.sponsered_to_helper') }}</span>
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