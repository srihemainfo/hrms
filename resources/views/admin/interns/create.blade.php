@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.intern.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.interns.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name_id">{{ trans('cruds.intern.fields.name') }}</label>
                <select class="form-control select2 {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name_id" id="name_id">
                    @foreach($names as $id => $entry)
                        <option value="{{ $id }}" {{ old('name_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.intern.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="topic">{{ trans('cruds.intern.fields.topic') }}</label>
                <input class="form-control {{ $errors->has('topic') ? 'is-invalid' : '' }}" type="text" name="topic" id="topic" value="{{ old('topic', '') }}">
                @if($errors->has('topic'))
                    <span class="text-danger">{{ $errors->first('topic') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.intern.fields.topic_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="from_date">{{ trans('cruds.intern.fields.from_date') }}</label>
                <input class="form-control date {{ $errors->has('from_date') ? 'is-invalid' : '' }}" type="text" name="from_date" id="from_date" value="{{ old('from_date') }}">
                @if($errors->has('from_date'))
                    <span class="text-danger">{{ $errors->first('from_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.intern.fields.from_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="to_date">{{ trans('cruds.intern.fields.to_date') }}</label>
                <input class="form-control date {{ $errors->has('to_date') ? 'is-invalid' : '' }}" type="text" name="to_date" id="to_date" value="{{ old('to_date') }}">
                @if($errors->has('to_date'))
                    <span class="text-danger">{{ $errors->first('to_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.intern.fields.to_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="progress_report">{{ trans('cruds.intern.fields.progress_report') }}</label>
                <input class="form-control {{ $errors->has('progress_report') ? 'is-invalid' : '' }}" type="text" name="progress_report" id="progress_report" value="{{ old('progress_report', '') }}">
                @if($errors->has('progress_report'))
                    <span class="text-danger">{{ $errors->first('progress_report') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.intern.fields.progress_report_helper') }}</span>
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