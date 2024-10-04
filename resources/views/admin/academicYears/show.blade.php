@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.academicYear.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.academic-years.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.academicYear.fields.id') }}
                        </th>
                        <td>
                            {{ $academicYear->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.academicYear.fields.name') }}
                        </th>
                        <td>
                            {{ $academicYear->name }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.academic-years.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#academic_course_enroll_masters" role="tab" data-toggle="tab">
                {{ trans('cruds.courseEnrollMaster.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="academic_course_enroll_masters">
            @includeIf('admin.academicYears.relationships.academicCourseEnrollMasters', ['courseEnrollMasters' => $academicYear->academicCourseEnrollMasters])
        </div>
    </div>
</div>

@endsection