@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.subject.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.subjects.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label for="syllabus_id">{{ trans('cruds.subject.fields.syllabus') }}</label>
                            <select class="form-control select2" name="syllabus_id" id="syllabus_id">
                                @foreach($syllabi as $id => $entry)
                                    <option value="{{ $id }}" {{ old('syllabus_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('syllabus'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('syllabus') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.subject.fields.syllabus_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="name">{{ trans('cruds.subject.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', '') }}">
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.subject.fields.name_helper') }}</span>
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