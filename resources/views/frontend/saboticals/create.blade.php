@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.sabotical.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.saboticals.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label for="name_id">{{ trans('cruds.sabotical.fields.name') }}</label>
                            <select class="form-control select2" name="name_id" id="name_id">
                                @foreach($names as $id => $entry)
                                    <option value="{{ $id }}" {{ old('name_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sabotical.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="topic">{{ trans('cruds.sabotical.fields.topic') }}</label>
                            <input class="form-control" type="text" name="topic" id="topic" value="{{ old('topic', '') }}">
                            @if($errors->has('topic'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('topic') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sabotical.fields.topic_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="eligiblity_approve">{{ trans('cruds.sabotical.fields.eligiblity_approve') }}</label>
                            <input class="form-control" type="text" name="eligiblity_approve" id="eligiblity_approve" value="{{ old('eligiblity_approve', '') }}">
                            @if($errors->has('eligiblity_approve'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('eligiblity_approve') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sabotical.fields.eligiblity_approve_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="from">{{ trans('cruds.sabotical.fields.from') }}</label>
                            <input class="form-control date" type="text" name="from" id="from" value="{{ old('from') }}">
                            @if($errors->has('from'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('from') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sabotical.fields.from_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="to">{{ trans('cruds.sabotical.fields.to') }}</label>
                            <input class="form-control date" type="text" name="to" id="to" value="{{ old('to') }}">
                            @if($errors->has('to'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('to') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sabotical.fields.to_helper') }}</span>
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