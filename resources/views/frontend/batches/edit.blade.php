@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.batch.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.batches.update", [$batch->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="from">{{ trans('cruds.batch.fields.from') }}</label>
                            <input class="form-control" type="number" name="from" id="from" value="{{ old('from', $batch->from) }}" step="1" required>
                            @if($errors->has('from'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('from') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.batch.fields.from_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="to">{{ trans('cruds.batch.fields.to') }}</label>
                            <input class="form-control" type="number" name="to" id="to" value="{{ old('to', $batch->to) }}" step="1" required>
                            @if($errors->has('to'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('to') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.batch.fields.to_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="name">{{ trans('cruds.batch.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $batch->name) }}" required>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.batch.fields.name_helper') }}</span>
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