@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.motherTongue.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.mother-tongues.update", [$motherTongue->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="mother_tongue">{{ trans('cruds.motherTongue.fields.mother_tongue') }}</label>
                            <input class="form-control" type="text" name="mother_tongue" id="mother_tongue" value="{{ old('mother_tongue', $motherTongue->mother_tongue) }}">
                            @if($errors->has('mother_tongue'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mother_tongue') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.motherTongue.fields.mother_tongue_helper') }}</span>
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