@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.sponser.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.sponsers.update", [$sponser->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="user_name_id">{{ trans('cruds.sponser.fields.user_name') }}</label>
                            <select class="form-control select2" name="user_name_id" id="user_name_id">
                                @foreach($user_names as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('user_name_id') ? old('user_name_id') : $sponser->user_name->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('user_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sponser.fields.user_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="sponser_type">{{ trans('cruds.sponser.fields.sponser_type') }}</label>
                            <input class="form-control" type="text" name="sponser_type" id="sponser_type" value="{{ old('sponser_type', $sponser->sponser_type) }}">
                            @if($errors->has('sponser_type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('sponser_type') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sponser.fields.sponser_type_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="sponser_name">{{ trans('cruds.sponser.fields.sponser_name') }}</label>
                            <input class="form-control" type="text" name="sponser_name" id="sponser_name" value="{{ old('sponser_name', $sponser->sponser_name) }}">
                            @if($errors->has('sponser_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('sponser_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sponser.fields.sponser_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="sponsered_items">{{ trans('cruds.sponser.fields.sponsered_items') }}</label>
                            <input class="form-control" type="text" name="sponsered_items" id="sponsered_items" value="{{ old('sponsered_items', $sponser->sponsered_items) }}">
                            @if($errors->has('sponsered_items'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('sponsered_items') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sponser.fields.sponsered_items_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="sponsered_to">{{ trans('cruds.sponser.fields.sponsered_to') }}</label>
                            <input class="form-control" type="text" name="sponsered_to" id="sponsered_to" value="{{ old('sponsered_to', $sponser->sponsered_to) }}">
                            @if($errors->has('sponsered_to'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('sponsered_to') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection