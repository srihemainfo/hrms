@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.hrmRequestLeaf.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.hrm-request-leaves.update", [$hrmRequestLeaf->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="user_id">{{ trans('cruds.hrmRequestLeaf.fields.user') }}</label>
                            <select class="form-control select2" name="user_id" id="user_id">
                                @foreach($users as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('user_id') ? old('user_id') : $hrmRequestLeaf->user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('user'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.hrmRequestLeaf.fields.user_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="from_date">{{ trans('cruds.hrmRequestLeaf.fields.from_date') }}</label>
                            <input class="form-control" type="text" name="from_date" id="from_date" value="{{ old('from_date', $hrmRequestLeaf->from_date) }}">
                            @if($errors->has('from_date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('from_date') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.hrmRequestLeaf.fields.from_date_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="to_date">{{ trans('cruds.hrmRequestLeaf.fields.to_date') }}</label>
                            <input class="form-control" type="text" name="to_date" id="to_date" value="{{ old('to_date', $hrmRequestLeaf->to_date) }}">
                            @if($errors->has('to_date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('to_date') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.hrmRequestLeaf.fields.to_date_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="reason">{{ trans('cruds.hrmRequestLeaf.fields.reason') }}</label>
                            <input class="form-control" type="text" name="reason" id="reason" value="{{ old('reason', $hrmRequestLeaf->reason) }}">
                            @if($errors->has('reason'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('reason') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.hrmRequestLeaf.fields.reason_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="approved_by">{{ trans('cruds.hrmRequestLeaf.fields.approved_by') }}</label>
                            <input class="form-control" type="text" name="approved_by" id="approved_by" value="{{ old('approved_by', $hrmRequestLeaf->approved_by) }}">
                            @if($errors->has('approved_by'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('approved_by') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.hrmRequestLeaf.fields.approved_by_helper') }}</span>
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