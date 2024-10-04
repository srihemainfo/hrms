@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.toolsDegreeType.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.tools-degree-types.update", [$toolsDegreeType->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.toolsDegreeType.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $toolsDegreeType->name) }}" required>
                @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('roles') }}</span>
                    @endif
                <span class="help-block">{{ trans('cruds.toolsDegreeType.fields.name_helper') }}</span>
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