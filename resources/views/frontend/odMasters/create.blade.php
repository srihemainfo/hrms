@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.odMaster.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.od-masters.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="name">{{ trans('cruds.odMaster.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.odMaster.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="level_1_role">{{ trans('cruds.odMaster.fields.level_1_role') }}</label>
                            <input class="form-control" type="text" name="level_1_role" id="level_1_role" value="{{ old('level_1_role', '') }}" required>
                            @if($errors->has('level_1_role'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('level_1_role') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.odMaster.fields.level_1_role_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="level_2_role">{{ trans('cruds.odMaster.fields.level_2_role') }}</label>
                            <input class="form-control" type="text" name="level_2_role" id="level_2_role" value="{{ old('level_2_role', '') }}" required>
                            @if($errors->has('level_2_role'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('level_2_role') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.odMaster.fields.level_2_role_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="level_3_role">{{ trans('cruds.odMaster.fields.level_3_role') }}</label>
                            <input class="form-control" type="text" name="level_3_role" id="level_3_role" value="{{ old('level_3_role', '') }}" required>
                            @if($errors->has('level_3_role'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('level_3_role') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.odMaster.fields.level_3_role_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="approved_by">{{ trans('cruds.odMaster.fields.approved_by') }}</label>
                            <input class="form-control" type="text" name="approved_by" id="approved_by" value="{{ old('approved_by', '') }}" required>
                            @if($errors->has('approved_by'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('approved_by') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.odMaster.fields.approved_by_helper') }}</span>
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