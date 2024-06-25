@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.onlineCourse.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.online-courses.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.onlineCourse.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $onlineCourse->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.onlineCourse.fields.user_name') }}
                                    </th>
                                    <td>
                                        {{ $onlineCourse->user_name->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.onlineCourse.fields.course_name') }}
                                    </th>
                                    <td>
                                        {{ $onlineCourse->course_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.onlineCourse.fields.remark') }}
                                    </th>
                                    <td>
                                        {{ $onlineCourse->remark }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.onlineCourse.fields.from_date') }}
                                    </th>
                                    <td>
                                        {{ $onlineCourse->from_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.onlineCourse.fields.to_date') }}
                                    </th>
                                    <td>
                                        {{ $onlineCourse->to_date }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.online-courses.index') }}">
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