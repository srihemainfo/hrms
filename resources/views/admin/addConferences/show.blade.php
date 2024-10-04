@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.addConference.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.add-conferences.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.addConference.fields.id') }}
                        </th>
                        <td>
                            {{ $addConference->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.addConference.fields.user_name') }}
                        </th>
                        <td>
                            {{ $addConference->user_name->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.addConference.fields.topic_name') }}
                        </th>
                        <td>
                            {{ $addConference->topic_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.addConference.fields.location') }}
                        </th>
                        <td>
                            {{ $addConference->location }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.addConference.fields.conference_date') }}
                        </th>
                        <td>
                            {{ $addConference->conference_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.addConference.fields.contribution_of_conference') }}
                        </th>
                        <td>
                            {{ $addConference->contribution_of_conference }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.addConference.fields.project_name') }}
                        </th>
                        <td>
                            {{ $addConference->project_name }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.add-conferences.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection