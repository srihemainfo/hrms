@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.hrmRequestPermission.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.hrm-request-permissions.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.hrmRequestPermission.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $hrmRequestPermission->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.hrmRequestPermission.fields.user') }}
                                    </th>
                                    <td>
                                        {{ $hrmRequestPermission->user->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.hrmRequestPermission.fields.no_of_hours') }}
                                    </th>
                                    <td>
                                        {{ $hrmRequestPermission->no_of_hours }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.hrmRequestPermission.fields.from_date') }}
                                    </th>
                                    <td>
                                        {{ $hrmRequestPermission->from_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.hrmRequestPermission.fields.reason') }}
                                    </th>
                                    <td>
                                        {{ $hrmRequestPermission->reason }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.hrmRequestPermission.fields.approved_by') }}
                                    </th>
                                    <td>
                                        {{ $hrmRequestPermission->approved_by }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.hrm-request-permissions.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection