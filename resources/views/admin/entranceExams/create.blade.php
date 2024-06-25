@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.entranceExam.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.entrance-exams.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name_id">{{ trans('cruds.entranceExam.fields.name') }}</label>
                <select class="form-control select2 {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name_id" id="name_id">
                    @foreach($names as $id => $entry)
                        <option value="{{ $id }}" {{ old('name_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.entranceExam.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="exam_type_id">{{ trans('cruds.entranceExam.fields.exam_type') }}</label>
                <select class="form-control select2 {{ $errors->has('exam_type') ? 'is-invalid' : '' }}" name="exam_type_id" id="exam_type_id">
                    @foreach($exam_types as $id => $entry)
                        <option value="{{ $id }}" {{ old('exam_type_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('exam_type'))
                    <span class="text-danger">{{ $errors->first('exam_type') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.entranceExam.fields.exam_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="passing_year">{{ trans('cruds.entranceExam.fields.passing_year') }}</label>
                <input class="form-control date {{ $errors->has('passing_year') ? 'is-invalid' : '' }}" type="text" name="passing_year" id="passing_year" value="{{ old('passing_year') }}">
                @if($errors->has('passing_year'))
                    <span class="text-danger">{{ $errors->first('passing_year') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.entranceExam.fields.passing_year_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="scored_mark">{{ trans('cruds.entranceExam.fields.scored_mark') }}</label>
                <input class="form-control {{ $errors->has('scored_mark') ? 'is-invalid' : '' }}" type="text" name="scored_mark" id="scored_mark" value="{{ old('scored_mark', '') }}">
                @if($errors->has('scored_mark'))
                    <span class="text-danger">{{ $errors->first('scored_mark') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.entranceExam.fields.scored_mark_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="total_mark">{{ trans('cruds.entranceExam.fields.total_mark') }}</label>
                <input class="form-control {{ $errors->has('total_mark') ? 'is-invalid' : '' }}" type="text" name="total_mark" id="total_mark" value="{{ old('total_mark', '') }}">
                @if($errors->has('total_mark'))
                    <span class="text-danger">{{ $errors->first('total_mark') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.entranceExam.fields.total_mark_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="rank">{{ trans('cruds.entranceExam.fields.rank') }}</label>
                <input class="form-control {{ $errors->has('rank') ? 'is-invalid' : '' }}" type="text" name="rank" id="rank" value="{{ old('rank', '') }}">
                @if($errors->has('rank'))
                    <span class="text-danger">{{ $errors->first('rank') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.entranceExam.fields.rank_helper') }}</span>
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