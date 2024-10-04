@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.sabotical.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.saboticals.update", [$sabotical->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="name_id">{{ trans('cruds.sabotical.fields.name') }}</label>
                <select class="form-control select2 {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name_id" id="name_id">
                    @foreach($names as $id => $entry)
                        <option value="{{ $id }}" {{ (old('name_id') ? old('name_id') : $sabotical->name->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sabotical.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="topic">{{ trans('cruds.sabotical.fields.topic') }}</label>
                <input class="form-control {{ $errors->has('topic') ? 'is-invalid' : '' }}" type="text" name="topic" id="topic" value="{{ old('topic', $sabotical->topic) }}">
                @if($errors->has('topic'))
                    <span class="text-danger">{{ $errors->first('topic') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sabotical.fields.topic_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="eligiblity_approve">{{ trans('cruds.sabotical.fields.eligiblity_approve') }}</label>
                <input class="form-control {{ $errors->has('eligiblity_approve') ? 'is-invalid' : '' }}" type="text" name="eligiblity_approve" id="eligiblity_approve" value="{{ old('eligiblity_approve', $sabotical->eligiblity_approve) }}">
                @if($errors->has('eligiblity_approve'))
                    <span class="text-danger">{{ $errors->first('eligiblity_approve') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sabotical.fields.eligiblity_approve_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="from">{{ trans('cruds.sabotical.fields.from') }}</label>
                <input class="form-control date {{ $errors->has('from') ? 'is-invalid' : '' }}" type="text" name="from" id="from" value="{{ old('from', $sabotical->from) }}">
                @if($errors->has('from'))
                    <span class="text-danger">{{ $errors->first('from') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sabotical.fields.from_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="to">{{ trans('cruds.sabotical.fields.to') }}</label>
                <input class="form-control date {{ $errors->has('to') ? 'is-invalid' : '' }}" type="text" name="to" id="to" value="{{ old('to', $sabotical->to) }}">
                @if($errors->has('to'))
                    <span class="text-danger">{{ $errors->first('to') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sabotical.fields.to_helper') }}</span>
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