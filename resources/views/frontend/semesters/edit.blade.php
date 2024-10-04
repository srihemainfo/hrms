@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.semester.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.semesters.update", [$semester->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="semester">{{ trans('cruds.semester.fields.semester') }}</label>
                            <input class="form-control" type="text" name="semester" id="semester" value="{{ old('semester', $semester->semester) }}" required>
                            @if($errors->has('semester'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('semester') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.semester.fields.semester_helper') }}</span>
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