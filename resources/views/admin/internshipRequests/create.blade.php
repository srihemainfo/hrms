@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.internshipRequest.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.internship-requests.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="user">{{ trans('cruds.internshipRequest.fields.user') }}</label>
                <input class="form-control {{ $errors->has('user') ? 'is-invalid' : '' }}" type="text" name="user" id="user" value="{{ old('user', '') }}">
                @if($errors->has('user'))
                    <span class="text-danger">{{ $errors->first('user') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.internshipRequest.fields.user_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="from_date">{{ trans('cruds.internshipRequest.fields.from_date') }}</label>
                <input class="form-control {{ $errors->has('from_date') ? 'is-invalid' : '' }}" type="text" name="from_date" id="from_date" value="{{ old('from_date', '') }}">
                @if($errors->has('from_date'))
                    <span class="text-danger">{{ $errors->first('from_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.internshipRequest.fields.from_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="to_date">{{ trans('cruds.internshipRequest.fields.to_date') }}</label>
                <input class="form-control {{ $errors->has('to_date') ? 'is-invalid' : '' }}" type="text" name="to_date" id="to_date" value="{{ old('to_date', '') }}">
                @if($errors->has('to_date'))
                    <span class="text-danger">{{ $errors->first('to_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.internshipRequest.fields.to_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="level_1_userid">{{ trans('cruds.internshipRequest.fields.level_1_userid') }}</label>
                <input class="form-control {{ $errors->has('level_1_userid') ? 'is-invalid' : '' }}" type="text" name="level_1_userid" id="level_1_userid" value="{{ old('level_1_userid', '') }}">
                @if($errors->has('level_1_userid'))
                    <span class="text-danger">{{ $errors->first('level_1_userid') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.internshipRequest.fields.level_1_userid_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="level_2_userid">{{ trans('cruds.internshipRequest.fields.level_2_userid') }}</label>
                <input class="form-control {{ $errors->has('level_2_userid') ? 'is-invalid' : '' }}" type="text" name="level_2_userid" id="level_2_userid" value="{{ old('level_2_userid', '') }}">
                @if($errors->has('level_2_userid'))
                    <span class="text-danger">{{ $errors->first('level_2_userid') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.internshipRequest.fields.level_2_userid_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="level_3_userid">{{ trans('cruds.internshipRequest.fields.level_3_userid') }}</label>
                <input class="form-control {{ $errors->has('level_3_userid') ? 'is-invalid' : '' }}" type="text" name="level_3_userid" id="level_3_userid" value="{{ old('level_3_userid', '') }}">
                @if($errors->has('level_3_userid'))
                    <span class="text-danger">{{ $errors->first('level_3_userid') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.internshipRequest.fields.level_3_userid_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="approved_by">{{ trans('cruds.internshipRequest.fields.approved_by') }}</label>
                <input class="form-control {{ $errors->has('approved_by') ? 'is-invalid' : '' }}" type="text" name="approved_by" id="approved_by" value="{{ old('approved_by', '') }}">
                @if($errors->has('approved_by'))
                    <span class="text-danger">{{ $errors->first('approved_by') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.internshipRequest.fields.approved_by_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection