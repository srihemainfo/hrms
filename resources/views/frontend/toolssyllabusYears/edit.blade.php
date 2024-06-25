@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.toolssyllabusYear.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.toolssyllabus-years.update", [$toolssyllabusYear->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="year">{{ trans('cruds.toolssyllabusYear.fields.year') }}</label>
                            <input class="form-control" type="number" name="year" id="year" value="{{ old('year', $toolssyllabusYear->year) }}" step="1" required>
                            @if($errors->has('year'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('year') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.toolssyllabusYear.fields.year_helper') }}</span>
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