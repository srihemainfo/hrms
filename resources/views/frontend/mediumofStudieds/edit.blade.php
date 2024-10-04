@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.mediumofStudied.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.mediumof-studieds.update", [$mediumofStudied->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="medium">{{ trans('cruds.mediumofStudied.fields.medium') }}</label>
                            <input class="form-control" type="text" name="medium" id="medium" value="{{ old('medium', $mediumofStudied->medium) }}">
                            @if($errors->has('medium'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('medium') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mediumofStudied.fields.medium_helper') }}</span>
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