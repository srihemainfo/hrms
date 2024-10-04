@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.mediumofStudied.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.mediumof-studieds.update", [$mediumofStudied->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="medium">{{ trans('cruds.mediumofStudied.fields.medium') }}</label>
                <input class="form-control {{ $errors->has('medium') ? 'is-invalid' : '' }}" type="text" name="medium" id="medium" value="{{ old('medium', $mediumofStudied->medium) }}">
                @if($errors->has('medium'))
                    <span class="text-danger">{{ $errors->first('medium') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.mediumofStudied.fields.medium_helper') }}</span>
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