@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.addConference.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.add-conferences.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="user_name_id">{{ trans('cruds.addConference.fields.user_name') }}</label>
                <select class="form-control select2 {{ $errors->has('user_name') ? 'is-invalid' : '' }}" name="user_name_id" id="user_name_id">
                    @foreach($user_names as $id => $entry)
                        <option value="{{ $id }}" {{ old('user_name_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user_name'))
                    <span class="text-danger">{{ $errors->first('user_name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.addConference.fields.user_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="topic_name">{{ trans('cruds.addConference.fields.topic_name') }}</label>
                <input class="form-control {{ $errors->has('topic_name') ? 'is-invalid' : '' }}" type="text" name="topic_name" id="topic_name" value="{{ old('topic_name', '') }}">
                @if($errors->has('topic_name'))
                    <span class="text-danger">{{ $errors->first('topic_name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.addConference.fields.topic_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="location">{{ trans('cruds.addConference.fields.location') }}</label>
                <input class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}" type="text" name="location" id="location" value="{{ old('location', '') }}">
                @if($errors->has('location'))
                    <span class="text-danger">{{ $errors->first('location') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.addConference.fields.location_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="conference_date">{{ trans('cruds.addConference.fields.conference_date') }}</label>
                <input class="form-control date {{ $errors->has('conference_date') ? 'is-invalid' : '' }}" type="text" name="conference_date" id="conference_date" value="{{ old('conference_date') }}">
                @if($errors->has('conference_date'))
                    <span class="text-danger">{{ $errors->first('conference_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.addConference.fields.conference_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="contribution_of_conference">{{ trans('cruds.addConference.fields.contribution_of_conference') }}</label>
                <input class="form-control {{ $errors->has('contribution_of_conference') ? 'is-invalid' : '' }}" type="text" name="contribution_of_conference" id="contribution_of_conference" value="{{ old('contribution_of_conference', '') }}">
                @if($errors->has('contribution_of_conference'))
                    <span class="text-danger">{{ $errors->first('contribution_of_conference') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.addConference.fields.contribution_of_conference_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="project_name">{{ trans('cruds.addConference.fields.project_name') }}</label>
                <input class="form-control {{ $errors->has('project_name') ? 'is-invalid' : '' }}" type="text" name="project_name" id="project_name" value="{{ old('project_name', '') }}">
                @if($errors->has('project_name'))
                    <span class="text-danger">{{ $errors->first('project_name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.addConference.fields.project_name_helper') }}</span>
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