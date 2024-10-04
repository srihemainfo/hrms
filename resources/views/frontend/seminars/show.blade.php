@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.seminar.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.seminars.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.seminar.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $seminar->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.seminar.fields.user_name') }}
                                    </th>
                                    <td>
                                        {{ $seminar->user_name->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.seminar.fields.topic') }}
                                    </th>
                                    <td>
                                        {{ $seminar->topic }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.seminar.fields.remark') }}
                                    </th>
                                    <td>
                                        {{ $seminar->remark }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.seminar.fields.seminar_date') }}
                                    </th>
                                    <td>
                                        {{ $seminar->seminar_date }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.seminars.index') }}">
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