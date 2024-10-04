@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.section.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.sections.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">

                    <label class="required" for="course_id">Course</label>
                    <select class="form-control select2 {{ $errors->has('course') ? 'is-invalid' : '' }}"
                        name="course_id" id="course_id" required>
                        @foreach ($courses as $id => $entry)
                            <option value="{{ $id }}" {{ old('course_id') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('course'))
                        <span class="text-danger">{{ $errors->first('courses') }}</span>
                    @endif

                </div>
                <div class="form-group">
                    <label class="required" for="section">{{ trans('cruds.section.fields.section') }}</label>
                    <input class="form-control {{ $errors->has('section') ? 'is-invalid' : '' }}" type="text"
                        name="section" id="section" value="{{ old('section', '') }}" required>
                    @if ($errors->has('section'))
                        <span class="text-danger">{{ $errors->first('section') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.section.fields.section_helper') }}</span>
                </div>
                <div class="form-group">
                    <button class="btn btn-outline-danger" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
