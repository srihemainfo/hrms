@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.odRequest.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.od-requests.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.odRequest.fields.id') }}
                        </th>
                        <td>
                            {{ $odRequest->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.odRequest.fields.user') }}
                        </th>
                        <td>
                            {{ $odRequest->user }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.odRequest.fields.from_date') }}
                        </th>
                        <td>
                            {{ $odRequest->from_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.odRequest.fields.to_date') }}
                        </th>
                        <td>
                            {{ $odRequest->to_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.odRequest.fields.level_1_userid') }}
                        </th>
                        <td>
                            {{ $odRequest->level_1_userid }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.odRequest.fields.level_2_userid') }}
                        </th>
                        <td>
                            {{ $odRequest->level_2_userid }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.odRequest.fields.level_3_userid') }}
                        </th>
                        <td>
                            {{ $odRequest->level_3_userid }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.odRequest.fields.approved_by') }}
                        </th>
                        <td>
                            {{ $odRequest->approved_by }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.od-requests.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection