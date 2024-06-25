@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.industrialExperience.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.industrial-experiences.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.industrialExperience.fields.id') }}
                        </th>
                        <td>
                            {{ $industrialExperience->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.industrialExperience.fields.user_name') }}
                        </th>
                        <td>
                            {{ $industrialExperience->user_name->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.industrialExperience.fields.work_experience') }}
                        </th>
                        <td>
                            {{ $industrialExperience->work_experience }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.industrialExperience.fields.designation') }}
                        </th>
                        <td>
                            {{ $industrialExperience->designation }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.industrialExperience.fields.from') }}
                        </th>
                        <td>
                            {{ $industrialExperience->from }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.industrialExperience.fields.to') }}
                        </th>
                        <td>
                            {{ $industrialExperience->to }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.industrialExperience.fields.work_type') }}
                        </th>
                        <td>
                            {{ $industrialExperience->work_type }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.industrial-experiences.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection