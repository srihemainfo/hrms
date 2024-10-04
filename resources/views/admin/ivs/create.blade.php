@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.iv.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.ivs.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name_id">{{ trans('cruds.iv.fields.name') }}</label>
                <select class="form-control select2 {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name_id" id="name_id">
                    @foreach($names as $id => $entry)
                        <option value="{{ $id }}" {{ old('name_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.iv.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="topic">{{ trans('cruds.iv.fields.topic') }}</label>
                <input class="form-control {{ $errors->has('topic') ? 'is-invalid' : '' }}" type="text" name="topic" id="topic" value="{{ old('topic', '') }}">
                @if($errors->has('topic'))
                    <span class="text-danger">{{ $errors->first('topic') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.iv.fields.topic_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="location">{{ trans('cruds.iv.fields.location') }}</label>
                <input class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}" type="text" name="location" id="location" value="{{ old('location', '') }}">
                @if($errors->has('location'))
                    <span class="text-danger">{{ $errors->first('location') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.iv.fields.location_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="from_date">{{ trans('cruds.iv.fields.from_date') }}</label>
                <input class="form-control date {{ $errors->has('from_date') ? 'is-invalid' : '' }}" type="text" name="from_date" id="from_date" value="{{ old('from_date') }}">
                @if($errors->has('from_date'))
                    <span class="text-danger">{{ $errors->first('from_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.iv.fields.from_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="to_date">{{ trans('cruds.iv.fields.to_date') }}</label>
                <input class="form-control date {{ $errors->has('to_date') ? 'is-invalid' : '' }}" type="text" name="to_date" id="to_date" value="{{ old('to_date') }}">
                @if($errors->has('to_date'))
                    <span class="text-danger">{{ $errors->first('to_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.iv.fields.to_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="remarks">{{ trans('cruds.iv.fields.remarks') }}</label>
                <input class="form-control {{ $errors->has('remarks') ? 'is-invalid' : '' }}" type="text" name="remarks" id="remarks" value="{{ old('remarks', '') }}">
                @if($errors->has('remarks'))
                    <span class="text-danger">{{ $errors->first('remarks') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.iv.fields.remarks_helper') }}</span>
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