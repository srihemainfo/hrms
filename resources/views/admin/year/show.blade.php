@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.year.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.year.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.year.fields.id') }}
                        </th>
                        <td>
                            {{ $year->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.year.fields.name') }}
                        </th>
                        <td>
                            {{ $year->year }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.year.index') }}">
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
            <a class="nav-link" href="#degreetype_course_enroll_masters" role="tab" data-toggle="tab">
             {{--   {{ trans('cruds.courseEnrollMaster.title') }} --}} 
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="degreetype_course_enroll_masters">
          {{--  @includeIf('admin.year.relationships.degreetypeCourseEnrollMasters', ['courseEnrollMasters' => $year->degreetypeCourseEnrollMasters]) --}} 
        </div>
    </div>
</div>

@endsection