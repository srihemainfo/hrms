@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.testAnswer.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.test-answers.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="test_result_id">{{ trans('cruds.testAnswer.fields.test_result') }}</label>
                <select class="form-control select2 {{ $errors->has('test_result') ? 'is-invalid' : '' }}" name="test_result_id" id="test_result_id" required>
                    @foreach($test_results as $id => $entry)
                        <option value="{{ $id }}" {{ old('test_result_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('test_result'))
                    <span class="text-danger">{{ $errors->first('test_result') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.testAnswer.fields.test_result_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="question_id">{{ trans('cruds.testAnswer.fields.question') }}</label>
                <select class="form-control select2 {{ $errors->has('question') ? 'is-invalid' : '' }}" name="question_id" id="question_id" required>
                    @foreach($questions as $id => $entry)
                        <option value="{{ $id }}" {{ old('question_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('question'))
                    <span class="text-danger">{{ $errors->first('question') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.testAnswer.fields.question_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="option_id">{{ trans('cruds.testAnswer.fields.option') }}</label>
                <select class="form-control select2 {{ $errors->has('option') ? 'is-invalid' : '' }}" name="option_id" id="option_id" required>
                    @foreach($options as $id => $entry)
                        <option value="{{ $id }}" {{ old('option_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('option'))
                    <span class="text-danger">{{ $errors->first('option') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.testAnswer.fields.option_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('is_correct') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="is_correct" value="0">
                    <input class="form-check-input" type="checkbox" name="is_correct" id="is_correct" value="1" {{ old('is_correct', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_correct">{{ trans('cruds.testAnswer.fields.is_correct') }}</label>
                </div>
                @if($errors->has('is_correct'))
                    <span class="text-danger">{{ $errors->first('is_correct') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.testAnswer.fields.is_correct_helper') }}</span>
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