@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.collegeCalender.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.college-calenders.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.collegeCalender.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $collegeCalender->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.collegeCalender.fields.type') }}
                                    </th>
                                    <td>
                                        {{ $collegeCalender->type }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.collegeCalender.fields.academic_year') }}
                                    </th>
                                    <td>
                                        {{ $collegeCalender->academic_year }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.collegeCalender.fields.shift') }}
                                    </th>
                                    <td>
                                        {{ $collegeCalender->shift }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.collegeCalender.fields.semester_type') }}
                                    </th>
                                    <td>
                                        {{ $collegeCalender->semester_type }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.collegeCalender.fields.from_date') }}
                                    </th>
                                    <td>
                                        {{ $collegeCalender->from_date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.collegeCalender.fields.to_date') }}
                                    </th>
                                    <td>
                                        {{ $collegeCalender->to_date }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.college-calenders.index') }}">
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