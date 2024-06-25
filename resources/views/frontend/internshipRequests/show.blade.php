@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.internshipRequest.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.internship-requests.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.internshipRequest.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $internshipRequest->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.internshipRequest.fields.user') }}
                                    </th>
                                    <td>
                                        {{ $internshipRequest->user }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.internshipRequest.fields.from_date') }}
                                    </th>
                                    <td>
                                        {{ $internshipRequest->from_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.internshipRequest.fields.to_date') }}
                                    </th>
                                    <td>
                                        {{ $internshipRequest->to_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.internshipRequest.fields.level_1_userid') }}
                                    </th>
                                    <td>
                                        {{ $internshipRequest->level_1_userid }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.internshipRequest.fields.level_2_userid') }}
                                    </th>
                                    <td>
                                        {{ $internshipRequest->level_2_userid }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.internshipRequest.fields.level_3_userid') }}
                                    </th>
                                    <td>
                                        {{ $internshipRequest->level_3_userid }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.internshipRequest.fields.approved_by') }}
                                    </th>
                                    <td>
                                        {{ $internshipRequest->approved_by }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.internship-requests.index') }}">
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