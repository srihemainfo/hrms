@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.courseEnrollMaster.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.course-enroll-masters.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.courseEnrollMaster.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $courseEnrollMaster->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.courseEnrollMaster.fields.enroll_master_number') }}
                                    </th>
                                    <td>
                                        {{ $courseEnrollMaster->enroll_master_number }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.courseEnrollMaster.fields.degreetype') }}
                                    </th>
                                    <td>
                                        {{ $courseEnrollMaster->degreetype->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.courseEnrollMaster.fields.batch') }}
                                    </th>
                                    <td>
                                        {{ $courseEnrollMaster->batch->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.courseEnrollMaster.fields.academic') }}
                                    </th>
                                    <td>
                                        {{ $courseEnrollMaster->academic->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.courseEnrollMaster.fields.course') }}
                                    </th>
                                    <td>
                                        {{ $courseEnrollMaster->course->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.courseEnrollMaster.fields.department') }}
                                    </th>
                                    <td>
                                        {{ $courseEnrollMaster->department->name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.courseEnrollMaster.fields.semester') }}
                                    </th>
                                    <td>
                                        {{ $courseEnrollMaster->semester->semester ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.courseEnrollMaster.fields.section') }}
                                    </th>
                                    <td>
                                        {{ $courseEnrollMaster->section->section ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.course-enroll-masters.index') }}">
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