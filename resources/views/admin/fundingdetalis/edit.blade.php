@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.fundingdetali.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.fundingdetalis.update", [$fundingdetali->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="user_name_id">{{ trans('cruds.fundingdetali.fields.user_name') }}</label>
                <select class="form-control select2 {{ $errors->has('user_name') ? 'is-invalid' : '' }}" name="user_name_id" id="user_name_id">
                    @foreach($user_names as $id => $entry)
                        <option value="{{ $id }}" {{ (old('user_name_id') ? old('user_name_id') : $fundingdetali->user_name->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user_name'))
                    <span class="text-danger">{{ $errors->first('user_name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.fundingdetali.fields.user_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="topic">{{ trans('cruds.fundingdetali.fields.topic') }}</label>
                <input class="form-control {{ $errors->has('topic') ? 'is-invalid' : '' }}" type="text" name="topic" id="topic" value="{{ old('topic', $fundingdetali->topic) }}">
                @if($errors->has('topic'))
                    <span class="text-danger">{{ $errors->first('topic') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.fundingdetali.fields.topic_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="remark">{{ trans('cruds.fundingdetali.fields.remark') }}</label>
                <input class="form-control {{ $errors->has('remark') ? 'is-invalid' : '' }}" type="text" name="remark" id="remark" value="{{ old('remark', $fundingdetali->remark) }}">
                @if($errors->has('remark'))
                    <span class="text-danger">{{ $errors->first('remark') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.fundingdetali.fields.remark_helper') }}</span>
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