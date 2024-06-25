@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.toolsCourse.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.tools-courses.update", [$toolsCourse->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                {{-- <label class="required" for="department_id">{{ trans('cruds.toolsCourse.fields.department') }}</label> --}}
                <label class="required" for="department_id">Department</label>
                <select class="form-control select2 {{ $errors->has('department') ? 'is-invalid' : '' }}" name="department_id" id="department_id" required>
                    @foreach($departments as $id => $entry)
                        <option value="{{ $id }}" {{ (old('department_id') ? old('department_id') : $toolsCourse->department->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('department'))
                    <span class="text-danger">{{ $errors->first('department') }}</span>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.toolsCourse.fields.department_helper') }}</span> --}}
            </div>
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.toolsCourse.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $toolsCourse->name) }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.toolsCourse.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="short_form">Course in Short Form</label>
                <input class="form-control {{ $errors->has('short_form') ? 'is-invalid' : '' }}" type="text" name="short_form" id="short_form" value="{{ old('short_form', $toolsCourse->short_form) }}" required>
                @if($errors->has('short_form'))
                    <span class="text-danger">{{ $errors->first('short_form') }}</span>
                @endif
                
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
