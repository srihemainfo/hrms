@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.workshop.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.workshops.update", [$workshop->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="user_name_id">{{ trans('cruds.workshop.fields.user_name') }}</label>
                            <select class="form-control select2" name="user_name_id" id="user_name_id">
                                @foreach($user_names as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('user_name_id') ? old('user_name_id') : $workshop->user_name->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('user_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.workshop.fields.user_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="topic">{{ trans('cruds.workshop.fields.topic') }}</label>
                            <input class="form-control" type="text" name="topic" id="topic" value="{{ old('topic', $workshop->topic) }}">
                            @if($errors->has('topic'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('topic') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.workshop.fields.topic_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="remarks">{{ trans('cruds.workshop.fields.remarks') }}</label>
                            <input class="form-control" type="text" name="remarks" id="remarks" value="{{ old('remarks', $workshop->remarks) }}">
                            @if($errors->has('remarks'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('remarks') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.workshop.fields.remarks_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="from_date">{{ trans('cruds.workshop.fields.from_date') }}</label>
                            <input class="form-control date" type="text" name="from_date" id="from_date" value="{{ old('from_date', $workshop->from_date) }}">
                            @if($errors->has('from_date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('from_date') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.workshop.fields.from_date_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="to_date">{{ trans('cruds.workshop.fields.to_date') }}</label>
                            <input class="form-control date" type="text" name="to_date" id="to_date" value="{{ old('to_date', $workshop->to_date) }}">
                            @if($errors->has('to_date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('to_date') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.workshop.fields.to_date_helper') }}</span>
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