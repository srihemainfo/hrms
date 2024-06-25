@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.faqQuestion.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.feedback_questions.update', [$datas->id]) }}"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="" for="category">{{ trans('cruds.faqQuestion.fields.category') }}</label>
                    <select class="form-control select2 {{ $errors->has('category') ? 'is-invalid' : '' }}" name="category"
                        id="category">
                        <option value=""
                            {{ ($datas->category != '' ? $datas->category : '') == '' ? 'selected' : '' }}>Please
                            select</option>
                        <option value="Staff"
                            {{ ($datas->category != '' ? $datas->category : '') == 'Staff' ? 'selected' : '' }}>Staff
                        </option>
                        <option value="Management"
                            {{ ($datas->category != '' ? $datas->category : '') == 'Management' ? 'selected' : '' }}>
                            Management
                        </option>
                        <option value="Events"
                            {{ ($datas->category != '' ? $datas->category : '') == 'Events' ? 'selected' : '' }}>Events
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="questions" class="required"> Questions </label>
                    <input class="form-control {{ $errors->has('questions') ? 'is-invalid' : '' }}" type="text"
                        name="questions" id="questions" value="{{ $datas->questions != '' ? $datas->questions : '' }}">
                    @if ($errors->has('questions'))
                        <span class="text-danger">{{ $errors->first('questions') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.expense.fields.description_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="answertype" class="required">Answer Type</label>
                    <select class="form-control select2 {{ $errors->has('answertype') ? 'is-invalid' : '' }}"
                        name="answertype" id="answertype">
                        <option value="" {{ old('answertype') == '' ? 'selected' : '' }}>Please select</option>
                        <option value="Choose" {{ ($datas->answertype != '' ? $datas->answertype : '')== 'Choose' ? 'selected' : '' }}>Choose</option>
                        <option value="typing" {{ ($datas->answertype != '' ? $datas->answertype : '')== 'typing' ? 'selected' : '' }}>Typing</option>
                        {{-- <option value="" {{ old('answertype') == 'Events' ? 'selected' : '' }}>Events</option> --}}
                    </select>
                    <span class="help-block">{{ trans('cruds.expense.fields.description_helper') }}</span>
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
