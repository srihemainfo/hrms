@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.entranceExam.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.entrance-exams.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label for="name_id">{{ trans('cruds.entranceExam.fields.name') }}</label>
                            <select class="form-control select2" name="name_id" id="name_id">
                                @foreach($names as $id => $entry)
                                    <option value="{{ $id }}" {{ old('name_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.entranceExam.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="exam_type_id">{{ trans('cruds.entranceExam.fields.exam_type') }}</label>
                            <select class="form-control select2" name="exam_type_id" id="exam_type_id">
                                @foreach($exam_types as $id => $entry)
                                    <option value="{{ $id }}" {{ old('exam_type_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('exam_type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('exam_type') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.entranceExam.fields.exam_type_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="passing_year">{{ trans('cruds.entranceExam.fields.passing_year') }}</label>
                            <input class="form-control date" type="text" name="passing_year" id="passing_year" value="{{ old('passing_year') }}">
                            @if($errors->has('passing_year'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('passing_year') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.entranceExam.fields.passing_year_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="scored_mark">{{ trans('cruds.entranceExam.fields.scored_mark') }}</label>
                            <input class="form-control" type="text" name="scored_mark" id="scored_mark" value="{{ old('scored_mark', '') }}">
                            @if($errors->has('scored_mark'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('scored_mark') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.entranceExam.fields.scored_mark_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="total_mark">{{ trans('cruds.entranceExam.fields.total_mark') }}</label>
                            <input class="form-control" type="text" name="total_mark" id="total_mark" value="{{ old('total_mark', '') }}">
                            @if($errors->has('total_mark'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('total_mark') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.entranceExam.fields.total_mark_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="rank">{{ trans('cruds.entranceExam.fields.rank') }}</label>
                            <input class="form-control" type="text" name="rank" id="rank" value="{{ old('rank', '') }}">
                            @if($errors->has('rank'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('rank') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection