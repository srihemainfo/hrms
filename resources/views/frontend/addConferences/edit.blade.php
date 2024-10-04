@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.addConference.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.add-conferences.update", [$addConference->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="user_name_id">{{ trans('cruds.addConference.fields.user_name') }}</label>
                            <select class="form-control select2" name="user_name_id" id="user_name_id">
                                @foreach($user_names as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('user_name_id') ? old('user_name_id') : $addConference->user_name->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('user_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.addConference.fields.user_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="topic_name">{{ trans('cruds.addConference.fields.topic_name') }}</label>
                            <input class="form-control" type="text" name="topic_name" id="topic_name" value="{{ old('topic_name', $addConference->topic_name) }}">
                            @if($errors->has('topic_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('topic_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.addConference.fields.topic_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="location">{{ trans('cruds.addConference.fields.location') }}</label>
                            <input class="form-control" type="text" name="location" id="location" value="{{ old('location', $addConference->location) }}">
                            @if($errors->has('location'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('location') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.addConference.fields.location_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="conference_date">{{ trans('cruds.addConference.fields.conference_date') }}</label>
                            <input class="form-control date" type="text" name="conference_date" id="conference_date" value="{{ old('conference_date', $addConference->conference_date) }}">
                            @if($errors->has('conference_date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('conference_date') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.addConference.fields.conference_date_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="contribution_of_conference">{{ trans('cruds.addConference.fields.contribution_of_conference') }}</label>
                            <input class="form-control" type="text" name="contribution_of_conference" id="contribution_of_conference" value="{{ old('contribution_of_conference', $addConference->contribution_of_conference) }}">
                            @if($errors->has('contribution_of_conference'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('contribution_of_conference') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.addConference.fields.contribution_of_conference_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="project_name">{{ trans('cruds.addConference.fields.project_name') }}</label>
                            <input class="form-control" type="text" name="project_name" id="project_name" value="{{ old('project_name', $addConference->project_name) }}">
                            @if($errors->has('project_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('project_name') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection