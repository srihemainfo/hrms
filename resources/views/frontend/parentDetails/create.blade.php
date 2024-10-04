@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.parentDetail.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.parent-details.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label for="father_name">{{ trans('cruds.parentDetail.fields.father_name') }}</label>
                            <input class="form-control" type="text" name="father_name" id="father_name" value="{{ old('father_name', '') }}">
                            @if($errors->has('father_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('father_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.parentDetail.fields.father_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="father_mobile_no">{{ trans('cruds.parentDetail.fields.father_mobile_no') }}</label>
                            <input class="form-control" type="text" name="father_mobile_no" id="father_mobile_no" value="{{ old('father_mobile_no', '') }}">
                            @if($errors->has('father_mobile_no'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('father_mobile_no') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.parentDetail.fields.father_mobile_no_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="fathers_occupation">{{ trans('cruds.parentDetail.fields.fathers_occupation') }}</label>
                            <input class="form-control" type="text" name="fathers_occupation" id="fathers_occupation" value="{{ old('fathers_occupation', '') }}">
                            @if($errors->has('fathers_occupation'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('fathers_occupation') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.parentDetail.fields.fathers_occupation_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="mother_name">{{ trans('cruds.parentDetail.fields.mother_name') }}</label>
                            <input class="form-control" type="text" name="mother_name" id="mother_name" value="{{ old('mother_name', '') }}">
                            @if($errors->has('mother_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mother_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.parentDetail.fields.mother_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="mother_mobile_no">{{ trans('cruds.parentDetail.fields.mother_mobile_no') }}</label>
                            <input class="form-control" type="text" name="mother_mobile_no" id="mother_mobile_no" value="{{ old('mother_mobile_no', '') }}">
                            @if($errors->has('mother_mobile_no'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mother_mobile_no') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.parentDetail.fields.mother_mobile_no_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="mothers_occupation">{{ trans('cruds.parentDetail.fields.mothers_occupation') }}</label>
                            <input class="form-control" type="text" name="mothers_occupation" id="mothers_occupation" value="{{ old('mothers_occupation', '') }}">
                            @if($errors->has('mothers_occupation'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mothers_occupation') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.parentDetail.fields.mothers_occupation_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="guardian_name">{{ trans('cruds.parentDetail.fields.guardian_name') }}</label>
                            <input class="form-control" type="text" name="guardian_name" id="guardian_name" value="{{ old('guardian_name', '') }}">
                            @if($errors->has('guardian_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('guardian_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.parentDetail.fields.guardian_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="guardian_mobile_no">{{ trans('cruds.parentDetail.fields.guardian_mobile_no') }}</label>
                            <input class="form-control" type="text" name="guardian_mobile_no" id="guardian_mobile_no" value="{{ old('guardian_mobile_no', '') }}">
                            @if($errors->has('guardian_mobile_no'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('guardian_mobile_no') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.parentDetail.fields.guardian_mobile_no_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="gaurdian_occupation">{{ trans('cruds.parentDetail.fields.gaurdian_occupation') }}</label>
                            <input class="form-control" type="text" name="gaurdian_occupation" id="gaurdian_occupation" value="{{ old('gaurdian_occupation', '') }}">
                            @if($errors->has('gaurdian_occupation'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('gaurdian_occupation') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.parentDetail.fields.gaurdian_occupation_helper') }}</span>
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